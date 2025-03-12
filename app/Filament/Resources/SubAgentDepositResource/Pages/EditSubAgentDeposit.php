<?php

namespace App\Filament\Resources\SubAgentDepositResource\Pages;

use App\Filament\Resources\SubAgentDepositResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubAgentDeposit extends EditRecord
{
    protected static string $resource = SubAgentDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
