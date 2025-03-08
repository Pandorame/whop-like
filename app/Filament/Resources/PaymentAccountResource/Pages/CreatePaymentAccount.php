<?php

namespace App\Filament\Resources\PaymentAccountResource\Pages;

use App\Filament\Resources\PaymentAccountResource;
use Auth;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentAccount extends CreateRecord
{
    protected static string $resource = PaymentAccountResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::guard('admin')->user();
        $data['admin_id'] = $user->id;
        return $data;
    }
}
