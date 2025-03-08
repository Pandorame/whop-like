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
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MyDepositReosourceResource extends Resource
{
    protected static ?string $model = Deposit::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Deposit';

    protected static ?string $navigationGroup = 'Transaction Management';

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        return parent::getEloquentQuery()->when($admin->parent_id,fn($q) => $q->where('admin_id',$admin->id));
    }

    public static function form(Form $form): Form
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();

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
            ->columns([


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
                    Forms\Components\FileUpload::make('slip')
                        ->disk('do')->hiddenLabel(true)->disabled()
                        ->previewable()->imagePreviewHeight('550'),
                    ])
                    ->button()->icon('heroicon-o-viewfinder-circle')->modalWidth('md'),


                Tables\Actions\Action::make('Complete')->action(function(Deposit $record){
                    $admin = Admin::where('id',$record->admin_id)->first();
                    if (!$admin){
                        Notification::make()->title('Wallet Not Found')->color('danger')->send();
                        return;
                    }
                    $comissionFees =( $admin->commission_percentage / 100 ) *  $record->amount;
                    $admin->increment('wallet',$record->amount + $comissionFees);
                    $record->status = DepositStatus::Completed;
                    $record->update();

                    $transition = new Transation();
                    $transition->transationable_id = $admin->id;
                    $transition->transationable_type = get_class($admin);
                    $transition->amount = $record->amount;
                    $transition->type = TransitionType::CashIn;
                    $transition->save();

                    Notification::make()->title('Delivered Deposit Completed')->color('success')->send();
                })->button()->icon('heroicon-o-check-circle')->outlined()->color('success')
                    ->visible(fn(Deposit $deposit)=> $deposit->status == DepositStatus::Processing &&  $deposit->to_admin_id == \Illuminate\Support\Facades\Auth::user()->id),

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
            'index' => Pages\ListMyDepositReosources::route('/'),
            'create' => Pages\CreateMyDepositReosource::route('/create'),
            'edit' => Pages\EditMyDepositReosource::route('/{record}/edit'),
        ];
    }
}
