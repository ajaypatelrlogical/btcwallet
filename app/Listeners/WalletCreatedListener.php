<?php

namespace App\Listeners;

use App\Traits\HasWalletHelpers;
use Bavix\Wallet\Internal\Events\WalletCreatedEvent;
use Bavix\Wallet\Models\Wallet;

class WalletCreatedListener
{
    use HasWalletHelpers;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WalletCreatedEvent $event): void
    {
        
        Wallet::find($event->getWalletId())->update([

        ]);
    }
}
