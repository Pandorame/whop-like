<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawResource\Pages;
use App\Filament\Resources\WithdrawResource\RelationManagers;
use App\Helpers\Enums\TransitionType;
use App\Helpers\Enums\WithdrawStatus;
use App\Models\Admin;
use App\Models\Transation;
use App\Models\Withdraw;
use DB;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WithdrawResource extends Resource
{
    protected static ?string $model = Withdraw::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Withdraw Management';


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
                ->where(column: function ($query) use ($admin) {
                    $query->where('admin_id', $admin->id)->whereNull('user_id');
                })
                // ->orWhere('to_admin_id', $admin->id)
                ;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'name'),

                Forms\Components\Hidden::make('admin_id')
                    ->default(Auth::id()),

                Forms\Components\Hidden::make('to_admin_id')
                    ->default(Auth::user()->parent_id),

                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->afterStateHydrated(fn(Get $get,Set $set) => $set('real_amount',$get('amount')))
                    ->numeric(),
                // Forms\Components\TextInput::make('transaction_fees')
                //     ->required()
                //     ->numeric()
                //     ->default(0.00),
                Forms\Components\Hidden::make('real_amount')
                    ->required()->default(0),

                Forms\Components\Select::make('payment_id')
                    ->relationship('payment', 'name')
                    ->required(),
                Forms\Components\TextInput::make('account_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('status')
                //     ->required()
                //     ->maxLength(255)
                //     ->default('processing'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([

                Tables\Columns\TextColumn::make('admin.name')
                     ->description(fn(Withdraw $withdraw) => $withdraw->admin?->email)
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->description(fn(Withdraw $withdraw) => $withdraw->transaction_fees)
                    ->sortable(),

                // Tables\Columns\TextColumn::make('real_amount')
                //     ->numeric()->fontFamily('mono')->size('lg')
                //     ->sortable(),

                Tables\Columns\TextColumn::make('payment.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('account_name')
                    ->description(fn(Withdraw $withdraw) => $withdraw->account_number)
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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

            ])
            ->actions([
                Tables\Actions\Action::make('Complete')->action(function(Withdraw $record){
                    $admin = Admin::where('id',$record->to_admin_id)->first();
                    if (!$admin){
                        Notification::make()->title('Wallet Not Found')->success()->send();
                        return;
                    }

                    DB::beginTransaction();
                    try{

                        $totalPoints = $record->amount;
                        $admin->increment('receive_wallet',$totalPoints);

                        $record->status = WithdrawStatus::Completed;
                        $record->update();

                        $transition = new Transation();
                        $transition->transationable_id = $admin->id;
                        $transition->transationable_type = get_class($admin);
                        $transition->amount = $record->amount;
                        $transition->type = TransitionType::CashOut;
                        $transition->save();

                        DB::commit();

                        Notification::make()->title('Delivered Deposit Completed')->success()->send();

                    }catch(Exception $e){

                        DB::rollback();
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }

                })->button()->icon('heroicon-o-check-circle')->outlined()->color('success')
                    ->extraAttributes(fn(Withdraw $withdraw)=> $withdraw->status == WithdrawStatus::Processing &&  $withdraw->to_admin_id == Auth::guard('admin')->id() ? array('class' =>  '') : array('class' => 'hidden'))
                     //->disabled(fn(Deposit $deposit)=> $deposit->to_admin_id != Auth::guard('admin')->id()) //$deposit->status != DepositStatus::Processing&&  $deposit->to_admin_id != Auth::guard('admin')->id()
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
            'index' => Pages\ListWithdraws::route('/'),
            'create' => Pages\CreateWithdraw::route('/create'),
            'edit' => Pages\EditWithdraw::route('/{record}/edit'),
        ];
    }
}
