<?php

namespace App\Filament\Resources\WithdrawResource\Pages;

use App\Filament\Resources\WithdrawResource;
use App\Models\Admin;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdraw extends CreateRecord
{
    protected static string $resource = WithdrawResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $admin = Admin::where('id',$data['admin_id'])->first();
        $admin->decrement('receive_wallet',$data['amount']);
        return $data;
    }
}
