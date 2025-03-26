<?php

namespace App\Filament\Resources\MyDepositReosourceResource\Pages;

use App\Filament\Resources\MyDepositReosourceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMyDepositReosource extends CreateRecord
{
    protected static string $resource = MyDepositReosourceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $admin = Auth::guard('admin')->user();
        $data['admin_id'] = $admin->id;
        $data['to_admin_id'] =$admin->parent_id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
