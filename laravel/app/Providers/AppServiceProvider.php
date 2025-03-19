<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\ModelActivityObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Order::observe(ModelActivityObserver::class);
    }
}
