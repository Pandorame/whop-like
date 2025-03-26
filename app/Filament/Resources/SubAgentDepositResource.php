<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubAgentDepositResource\Pages;
use App\Filament\Resources\SubAgentDepositResource\RelationManagers;
use App\Models\Deposit;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SubAgentDepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Deposit Management';

    public static function getPluralModelLabel(): string
    {
        return 'Deposits';
    }

    public static function getEloquentQuery(): Builder
    {
        $admin = Auth::guard('admin')->user();
        // return parent::getEloquentQuery()->when($admin->parent_id,fn($q) => $q->where('admin_id',$admin->id));
        if(in_array('super_admin',$admin->roles->pluck('name')->toArray())){
            return parent::getEloquentQuery()->where('to_admin_id',$admin->id)->whereNull('user_id');
        }

        return parent::getEloquentQuery()
                ->where(function ($query) use ($admin) {
                    $query->where('admin_id', $admin->id)->whereNull('user_id');
                })
                // ->orWhere('to_admin_id', $admin->id)
                ;
    }

    public static function form(Form $form): Form
    {
        return MyDepositReosourceResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return MyDepositReosourceResource::table($table);
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
            'index' => Pages\ListSubAgentDeposits::route('/'),
            'create' => Pages\CreateSubAgentDeposit::route('/create'),
            'edit' => Pages\EditSubAgentDeposit::route('/{record}/edit'),
        ];
    }
}
