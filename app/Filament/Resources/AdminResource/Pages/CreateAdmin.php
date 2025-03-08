<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::guard('admin')->user();

        if($user->level == null){
            $data['level'] = 'DTR';
        }elseif($user->level == "DTR"){
            $data['level'] = 'AGENT';
        }
        $data['parent_id'] = $user->id;

        return $data;
    }

}
