<?php

namespace App\Models;

use Bavix\Wallet\Models\Wallet as BaseWallet;

class Wallet extends BaseWallet
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
