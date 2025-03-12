<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubAgentWithdrawResource\Pages;
use App\Filament\Resources\SubAgentWithdrawResource\RelationManagers;
use App\Models\SubAgentWithdraw;
use App\Models\Withdraw;
use Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubAgentWithdrawResource extends Resource
{
    protected static ?string $model = Withdraw::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'SubAgentWithdraw';

    protected static ?string $navigationGroup = 'Withdraw Management';


    public static function getEloquentQuery(): Builder
    {
        $admin = Auth::guard('admin')->user();
        // return parent::getEloquentQuery()->when($admin->parent_id,fn($q) => $q->where('admin_id',$admin->id));
        if(in_array('super_admin',$admin->roles->pluck('name')->toArray())){
            return parent::getEloquentQuery()->where('to_admin_id',$admin->id)->whereNull('user_id');
        }

        return parent::getEloquentQuery()
                ->orWhere('to_admin_id', $admin->id)
                ;
    }

    public static function form(Form $form): Form
    {
        return WithdrawResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return WithdrawResource::table($table);
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
            'index' => Pages\ListSubAgentWithdraws::route('/'),
            'create' => Pages\CreateSubAgentWithdraw::route('/create'),
            'edit' => Pages\EditSubAgentWithdraw::route('/{record}/edit'),
        ];
    }
}
