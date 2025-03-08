<?php

namespace App\Filament\Resources\MyDepositReosourceResource\Pages;

use App\Filament\Resources\MyDepositReosourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyDepositReosources extends ListRecords
{
    protected static string $resource = MyDepositReosourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
