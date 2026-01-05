<?php

declare(strict_types=1);

use App\Console\Commands\RecurringTransfer;
use Illuminate\Support\Facades\Schedule;

Schedule::command(RecurringTransfer::class)
    ->dailyAt('02:00')
;