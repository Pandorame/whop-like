<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepositResource\Pages;
use App\Filament\Resources\DepositResource\RelationManagers;
use App\Helpers\Enums\DepositStatus;
use App\Helpers\Enums\TransitionType;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\Transation;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TransitionService;
use DB;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'User Deposits';

    protected static ?string $navigationGroup = 'Transaction Management';


    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $admin = Auth::guard('admin')->user();
        return parent::getEloquentQuery()->where('to_admin_id',$admin->id)->whereNotNull('user_id');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                // Forms\Components\Select::make('currency_id')
                //     ->relationship('currency', 'id')
                //     ->required(),
                Forms\Components\Select::make('payment_id')
                    ->relationship('payment', 'name')
                    ->required(),
                Forms\Components\Select::make('payment_account_id')
                    ->relationship('paymentAccount', 'name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('slip')
                    ->disk('do')
                    ->required()->imagePreviewHeight('400px'),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([

                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Agent')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('currency.type')
                //     ->description(fn(Deposit $deposit) => $deposit->currency->country->name)
                //     ->sortable(),
                Tables\Columns\TextColumn::make('payment.name')
                    ->description(fn(Deposit $deposit) => $deposit->paymentAccount->name)
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
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
//                        Forms\Components\TextInput::make('slip'),
                        Forms\Components\FileUpload::make('slip')
                            ->disk('do')
                            ->previewable()->imagePreviewHeight('550'),
                    ])
                    ->button()->icon('heroicon-o-viewfinder-circle')->modalWidth('md'),

                    Tables\Actions\Action::make('Complete')->action(function(Deposit $record){
                        $user = User::where('id',$record->user_id)->with('wallet')->first();
                        if (!$user){
                            Notification::make()->title('User Not Found')->color('danger')->send();
                            return;
                        }
                        if($record->toAdmin->wallet < $record->amount){
                            Notification::make()->title('You fund is insufficient')->color('danger')->send();
                            return;
                        }
                        DB::beginTransaction();
                        try{
                            $user->wallet->increment('wallet',$record->amount);
                            $record->status = DepositStatus::Completed;
                            $record->update();

                            $transition = new Transation();
                            $transition->transationable_id = $user->id;
                            $transition->transationable_type = get_class($user);
                            $transition->amount = $record->amount;
                            $transition->type = TransitionType::CashIn;
                            $transition->save();

                            $record->toAdmin->decrement('wallet',$record->amount);

                            DB::commit();

                            Notification::make()->title('Delivered Deposit Completed')->color('success')->send();

                        }catch(Exception $e){

                            DB::rollback();

                            Notification::make()->title($e->getMessage())->color('danger')->send();
                        }


                    })->button()->icon('heroicon-o-check-circle')->outlined()->color('success')
                        ->visible(fn(Deposit $deposit)=> $deposit->status == DepositStatus::Processing &&  $deposit->to_admin_id == Auth::user()->id),

                    Tables\Actions\Action::make('Cancelled')->action(function(Deposit $record){
                        $record->status = DepositStatus::Cancelled;
                        $record->update();

                        $transition = new Transation();
                        $transition->transationable_id = $record->admin_id;
                        $transition->transationable_type = get_class($record->admin);
                        $transition->amount = $record->amount;
                        $transition->type = TransitionType::CashIn;
                        $transition->save();

                        Notification::make()->title('Cancelled Deposit Completed')->color('danger')->send();
                    })->button()->icon('heroicon-o-x-circle')->outlined()->color('danger')->visible(fn(Deposit $deposit)=> $deposit->status == DepositStatus::Processing &&  $deposit->to_admin_id == \Illuminate\Support\Facades\Auth::user()->id),
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
            'index' => Pages\ListDeposits::route('/'),
            'create' => Pages\CreateDeposit::route('/create'),
            'edit' => Pages\EditDeposit::route('/{record}/edit'),
        ];
    }
}
