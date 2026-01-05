<?php

declare(strict_types=1);

namespace App\Enums;

enum WalletTransferType: string
{
    case ONCE = 'once';
    case RECURRING = 'recurring';

    public function isOnce(): bool
    {
        return $this === self::ONCE;
    }

    public function isRecurring(): bool
    {
        return $this === self::RECURRING;
    }
}
