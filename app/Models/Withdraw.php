<?php

namespace App\Models;

use App\Helpers\Enums\WithdrawStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    protected $casts = [
        'status' => WithdrawStatus::class
    ];

    public function user():BelongsTo
    {
        return  $this->belongsTo(User::class);
    }

    public function admin():BelongsTo
    {
        return  $this->belongsTo(Admin::class);
    }

    public function toAdmin():BelongsTo
    {
        return  $this->belongsTo(Admin::class,'to_admin_id','id');
    }

    public function payment():BelongsTo
    {
        return  $this->belongsTo(Payment::class);
    }
}
