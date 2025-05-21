<?php

namespace App\Providers;

use App\Listeners\WalletCreatedListener;
use Bavix\Wallet\Internal\Events\WalletCreatedEvent;
use Bavix\Wallet\Internal\Events\WalletCreatedEventInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

use Bavix\Wallet\Models\Wallet as BaseWallet;
use App\Models\Wallet;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseWallet::class, Wallet::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listener for wallet creation
        Event::listen(
            WalletCreatedEvent::class,
            [WalletCreatedListener::class, 'handle']
        );
    }
}
