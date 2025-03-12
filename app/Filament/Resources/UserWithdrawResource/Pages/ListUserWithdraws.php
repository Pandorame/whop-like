<?php

namespace App\Filament\Resources\UserWithdrawResource\Pages;

use App\Filament\Resources\UserWithdrawResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserWithdraws extends ListRecords
{
    protected static string $resource = UserWithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
