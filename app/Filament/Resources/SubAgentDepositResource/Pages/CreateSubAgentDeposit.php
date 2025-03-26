<?php

namespace App\Filament\Resources\SubAgentDepositResource\Pages;

use App\Filament\Resources\SubAgentDepositResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSubAgentDeposit extends CreateRecord
{
    protected static string $resource = SubAgentDepositResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $admin = Auth::guard('admin')->user();
        $data['admin_id'] = $admin->id;
        $data['to_admin_id'] =$admin->parent_id;
        return $data;
    }
}
