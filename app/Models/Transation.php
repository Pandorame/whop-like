<?php

namespace App\Models;

use App\Helpers\Enums\TransitionType;
use Illuminate\Database\Eloquent\Model;

class Transation extends Model
{
    protected $casts = [
        'status' => TransitionType::class
    ];
}
