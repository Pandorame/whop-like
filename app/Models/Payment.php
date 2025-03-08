<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    public function accounts():HasMany
    {
        return $this->hasMany(PaymentAccount::class);
    }

    public function currency():BelongsTo
    {
        return  $this->belongsTo(Currency::class);
    }
}
