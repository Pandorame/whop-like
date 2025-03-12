<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyDepositReosourceResource\Pages;
use App\Filament\Resources\MyDepositReosourceResource\RelationManagers;
use App\Helpers\Enums\DepositStatus;
use App\Helpers\Enums\TransitionType;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\MyDepositReosource;
use App\Models\Payment;
use App\Models\Transation;
use DB;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MyDepositReosourceResource extends Resource
{
    protected static ?string $model = Deposit::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $modelLabel = 'Deposit';

    public static function getPluralModelLabel(): string
    {
        $user = Auth::guard('admin')->user();
        return $user->level == null ? 'Distributor Deposits' :( $user->level == "DTR" ?  'Agent Deposits' : 'Deposits');
    }

    protected static ?string $navigationGroup = 'Deposit Management';

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $admin = Auth::guard('admin')->user();
        // return parent::getEloquentQuery()->when($admin->parent_id,fn($q) => $q->where('admin_id',$admin->id));
        if(in_array('super_admin',$admin->roles->pluck('name')->toArray())){
            return parent::getEloquentQuery()->where('to_admin_id',$admin->id)->whereNull('user_id');
        }

        return parent::getEloquentQuery()
                // ->where(function ($query) use ($admin) {
                //     $query->where('admin_id', $admin->id)->whereNull('user_id');
                // })
                ->orWhere('to_admin_id', $admin->id)
                ;
    }

    public static function form(Form $form): Form
    {
        $admin = Auth::guard('admin')->user();

        return $form
            ->schema([

                Forms\Components\Select::make('payment_id')
                    ->native(false)->live()
                    ->relationship('payment', 'name',
                        // modifyQueryUsing:fn($query) => $query->when($admin->parent_id,fn($q) => $q->where('admin_id',$admin->parent_id))
                        )
                    ->required(),

                Forms\Components\Select::make('payment_account_id')
                    ->native(false)->hidden(fn(Get $get) => blank($get('payment_id')))
                    ->relationship('paymentAccount', 'name',modifyQueryUsing: function ($query,Get $get) use ($admin) {
                        return $query->when($admin->parent_id, function ($q) use ($get,$admin) {
                            return $q->where('payment_id', $get('payment_id'))->where('admin_id',$admin->parent_id );
                        });
                    })
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('slip')
                    ->disk('do')
                    ->required()->imagePreviewHeight('400px'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([
                Tables\Columns\TextColumn::make('admin.name')
                ->label('From Agent')
                ->description(fn(Deposit $deposit) => $deposit->admin?->email)
                ->sortable(),

                Tables\Columns\TextColumn::make('toAdmin.name')
                ->label('To Agent')
                ->description(fn(Deposit $deposit) => $deposit->toAdmin?->email)
                ->sortable(),

                Tables\Columns\TextColumn::make('payment.name')
                    ->description(fn(Deposit $deposit) => $deposit->paymentAccount?->name)
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->numeric()->size('md')->fontFamily('mono')
                    ->sortable(),

                Tables\Columns\TextColumn::make('points')
                    ->numeric()->size('md')->fontFamily('mono')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Slip')
                ->fillForm(fn(Model $record) => [
                    'slip' => $record->slip,
                ])
                ->form(fn() => [
                    Forms\Components\FileUpload::make('slip')
                        ->disk('do')->hiddenLabel(true)->disabled()
                        ->previewable()->imagePreviewHeight('550'),
                    ])
                    ->button()->icon('heroicon-o-viewfinder-circle')->modalWidth('md'),


                Tables\Actions\Action::make('Complete')->action(function(Deposit $record){
                    $admin = Admin::where('id',$record->admin_id)->first();
                    if (!$admin){
                        Notification::make()->title('Wallet Not Found')->success()->send();
                        return;
                    }

                    if($record->toAdmin->wallet < $record->amount * 5500){
                        Notification::make()->title('You fund is insufficient')->danger()->send();
                        return;
                    }
                    DB::beginTransaction();
                    try{

                        $comissionFees = ( $admin->commission_percentage / 100 ) *  $record->amount * 5500;
                        $totalPoints = $record->amount * 5500 + $comissionFees;
                        $admin->increment('wallet',$totalPoints);
                        $record->status = DepositStatus::Completed;
                        $record->update();

                        $record->toAdmin->decrement('wallet',$totalPoints);

                        $transition = new Transation();
                        $transition->transationable_id = $admin->id;
                        $transition->transationable_type = get_class($admin);
                        $transition->amount = $record->amount * 5500;
                        $transition->type = TransitionType::CashIn;
                        $transition->save();

                        $transition = new Transation();
                        $transition->transationable_id = $admin->id;
                        $transition->transationable_type = get_class($admin);
                        $transition->amount = $comissionFees;
                        $transition->type = TransitionType::CashIn;
                        $transition->save();

                        DB::commit();

                        Notification::make()->title('Delivered Deposit Completed')->success()->send();

                    }catch(Exception $e){

                        DB::rollback();
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }

                })->button()->icon('heroicon-o-check-circle')->outlined()->color('success')
                    ->extraAttributes(fn(Deposit $deposit)=> $deposit->status == DepositStatus::Processing &&  $deposit->to_admin_id == Auth::guard('admin')->id() ? array('class' =>  '') : array('class' => 'hidden'))
                     //->disabled(fn(Deposit $deposit)=> $deposit->to_admin_id != Auth::guard('admin')->id()) //$deposit->status != DepositStatus::Processing&&  $deposit->to_admin_id != Auth::guard('admin')->id()
                    ,

                Tables\Actions\Action::make('Cancelled')->action(function(Deposit $record){
                    $record->status = DepositStatus::Cancelled;
                    $record->update();

                    $transition = new Transation();
                    $transition->transationable_id = $record->admin_id;
                    $transition->transationable_type = get_class($record->admin);
                    $transition->amount = $record->amount;
                    $transition->type = TransitionType::CashIn;
                    $transition->save();

                    Notification::make()->title('Cancelled Deposit Completed')->danger()->send();
                })->button()->icon('heroicon-o-x-circle')->outlined()->color('danger')
                ->extraAttributes(fn(Deposit $deposit)=> $deposit->status == DepositStatus::Processing &&  $deposit->to_admin_id == Auth::guard('admin')->id() ? array('class' =>  '') : array('class' => 'hidden'))
                    // ->visible(fn(Deposit $deposit)=> $deposit->status == DepositStatus::Processing &&  $deposit->to_admin_id == Auth::guard('admin')->id())
                    ,
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyDepositReosources::route('/'),
            'create' => Pages\CreateMyDepositReosource::route('/create'),
            'edit' => Pages\EditMyDepositReosource::route('/{record}/edit'),
        ];
    }
}
