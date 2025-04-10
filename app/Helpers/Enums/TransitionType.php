<?php

namespace App\Helpers\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TransitionType: string implements HasColor, HasIcon, HasLabel, HasDescription
{
    case CashIn = 'cash_in';
    case CashOut = 'cash_out';
    case CashInConfirmed = 'cash_in_confirmed';
    case CashOutConfirmed = 'cash_out_confirmed';
    case CashInCancelled = 'cash_in_cancelled';
    case CashOutCancelled = 'cash_out_cancelled';
    case Commission = 'commission';  // Added commission enum

    public function getLabel(): string
    {
        return match ($this) {
            self::CashIn => 'Cash In',
            self::CashOut => 'Cash Out',
            self::CashInConfirmed => 'Cash In Confirmed',
            self::CashOutConfirmed => 'Cash Out Confirmed',
            self::CashInCancelled => 'Cash In Cancelled',
            self::CashOutCancelled => 'Cash Out Cancelled',
            self::Commission => 'Commission',  // Added commission label
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::CashIn => 'warning',
            self::CashOut => 'success',
            self::CashInConfirmed => 'success',
            self::CashOutConfirmed => 'success',
            self::CashInCancelled => 'success',
            self::CashOutCancelled => 'success',
            self::Commission => 'primary',  // Added commission color
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CashIn => 'heroicon-m-arrow-down-left',
            self::CashOut => 'heroicon-m-arrow-up-right',
            self::CashInConfirmed => 'heroicon-m-check',
            self::CashOutConfirmed => 'heroicon-m-check',
            self::CashInCancelled => 'heroicon-m-x-circle',
            self::CashOutCancelled => 'heroicon-m-x-circle',
            self::Commission => 'heroicon-m-banknotes',  // Added commission icon
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::CashIn => 'Deposit Cash In',
            self::CashOut => 'Withdraw Cash Out',
            self::CashInConfirmed => 'CashInConfirmed',
            self::CashOutConfirmed => 'CashOutConfirmed',
            self::CashInCancelled => 'CashInCancelled',
            self::CashOutCancelled => 'CashOutCancelled',
            self::Commission => 'Commission Fee',  // Added commission description
        };
    }
}
