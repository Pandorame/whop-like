<?php

namespace App\Filament\Resources\SubAgentDepositResource\Pages;

use App\Filament\Resources\SubAgentDepositResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubAgentDeposits extends ListRecords
{
    protected static string $resource = SubAgentDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
