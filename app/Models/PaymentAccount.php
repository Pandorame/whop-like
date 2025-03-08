<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAccount extends Model
{
    use HasFactory;

    public function payment():BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function admin():BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
