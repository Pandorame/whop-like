<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function transitions(): MorphMany
    {
        return $this->morphMany(Transation::class, 'transationable');
    }

    public function wallet()
{
    return $this->hasOne(Wallet::class);
}

public function deposit($amount, $description = '')
{
    if (!$this->wallet) {
        $this->wallet()->create(['balance' => 0]);
    }

    $this->wallet->amount += $amount;
    $this->wallet->save();

    // Record transaction
    $this->wallet->transactions()->create([
        'amount' => $amount,
        'type' => 'deposit',
        'description' => $description,
        'balance_after' => $this->wallet->balance
    ]);

    return $this->wallet;
}

}
