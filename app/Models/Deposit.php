<?php

namespace App\Models;

use App\Helpers\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    protected $casts = [
        'status' => DepositStatus::class
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

    // public function currency():BelongsTo
    // {
    //     return  $this->belongsTo(Currency::class);
    // }

    public function payment():BelongsTo
    {
        return  $this->belongsTo(Payment::class);
    }

    public function paymentAccount():BelongsTo
    {
        return  $this->belongsTo(PaymentAccount::class);
    }
}
