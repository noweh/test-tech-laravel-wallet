<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\WalletTransactionType;
use App\Exceptions\InsufficientBalance;
use App\Mail\NotificationBalanceIsLow;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

readonly class PerformWalletTransfer
{
    public function __construct(protected PerformWalletTransaction $performWalletTransaction) {}

    /**
     * @throws InsufficientBalance
     */
    public function execute(User $sender, User $recipient, int $amount, string $reason): WalletTransfer
    {
        return DB::transaction(function () use ($sender, $recipient, $amount, $reason) {
            $transfer = WalletTransfer::create([
                'amount' => $amount,
                'source_id' => $sender->wallet->id,
                'target_id' => $recipient->wallet->id,
            ]);

            $this->performWalletTransaction->execute(
                wallet: $sender->wallet,
                type: WalletTransactionType::DEBIT,
                amount: $amount,
                reason: $reason,
                transfer: $transfer
            );

            $this->performWalletTransaction->execute(
                wallet: $recipient->wallet,
                type: WalletTransactionType::CREDIT,
                amount: $amount,
                reason: $reason,
                transfer: $transfer
            );

            // Email notification if balance is low
            if ($sender->wallet->balance < Wallet::MINIMUM_BALANCE_VALUE_BEFORE_MAIL) {
                Mail::to($sender->email)
                    ->send(new NotificationBalanceIsLow())
                ;
            }

            return $transfer;
        });
    }
}
