<?php

namespace App\Filament\Resources\SummitedCRsResource\Pages;

use App\Filament\Resources\SummitedCRsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSummitedCRs extends ListRecords
{
    protected static string $resource = SummitedCRsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
