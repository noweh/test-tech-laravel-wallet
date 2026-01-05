<?php

namespace App\Console\Commands;

use App\Actions\PerformWalletTransfer;
use App\Enums\WalletTransferType;
use App\Models\WalletTransfer;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RecurringTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recurring-transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var PerformWalletTransfer
     */
    private $performWalletTransfer;

    public function __construct(PerformWalletTransfer $performWalletTransfer) {
        parent::__construct();

        $this->performWalletTransfer = $performWalletTransfer;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WalletTransfer::where('type', WalletTransferType::RECURRING)->each(function ($walletTransfer) {
            $startDate = $walletTransfer->startDate;
            $endDate = $walletTransfer->endDate;
            $today = Carbon::now();

            // All X days according to the periodicity and the start date
            if ($startDate->lte($today) && $endDate->gte($today) &&
                $startDate->diffInDays($today) % $walletTransfer->recurring_frequency === 0
            ) {
                $this->performWalletTransfer->execute(
                    sender: $walletTransfer->source->wallet->user,
                    recipient: $walletTransfer->target->wallet->user,
                    amount: $walletTransfer->debit->amount,
                    reason: $walletTransfer->debit->reason,
                );
            }
        });
    }
}
