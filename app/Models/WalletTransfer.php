<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WalletTransferType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransfer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'type',
        'recurring_start_date',
        'recurring_end_date',
        'recurring_frequency'
    ];

    protected function casts(): array
    {
        return [
            'type' => WalletTransferType::class,
            'recurring_start_date' => 'date',
            'recurring_end_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Wallet>
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'source_id');
    }

    /**
     * @return BelongsTo<Wallet>
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'target_id');
    }

    /**
     * @return BelongsTo<WalletTransaction>
     */
    public function credit(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class, 'credit_id');
    }

    /**
     * @return BelongsTo<Wallet>
     */
    public function debit(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class, 'debit_id');
    }
}
