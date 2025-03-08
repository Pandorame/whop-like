<?php

namespace App\Filament\Resources\MyDepositReosourceResource\Pages;

use App\Filament\Resources\MyDepositReosourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyDepositReosource extends EditRecord
{
    protected static string $resource = MyDepositReosourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
