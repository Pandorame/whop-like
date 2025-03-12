<?php

namespace App\Filament\Resources\SubAgentWithdrawResource\Pages;

use App\Filament\Resources\SubAgentWithdrawResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubAgentWithdraws extends ListRecords
{
    protected static string $resource = SubAgentWithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
