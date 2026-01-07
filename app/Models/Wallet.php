<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\UserBalanceIsLow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance'
    ];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<WalletTransaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public static function booted(): void
    {
        static::updated(function (Wallet $wallet) {
            if ($wallet->wasChanged('balance') && $wallet->balance < 1000) {
                $wallet->user->notify(new UserBalanceIsLow());
            }
        });
    }
}
