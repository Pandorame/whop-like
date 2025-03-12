<?php

namespace App\Filament\Resources\UserWithdrawResource\Pages;

use App\Filament\Resources\UserWithdrawResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserWithdraw extends EditRecord
{
    protected static string $resource = UserWithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
