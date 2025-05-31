<?php

namespace App\Filament\Resources\SummitedCRsResource\Pages;

use App\Filament\Resources\SummitedCRsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSummitedCRs extends EditRecord
{
    protected static string $resource = SummitedCRsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
