<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Lab\PinjamAlat::observe(\App\Observers\PinjamAlatObserver::class);
        
        // Generic logging for other models
        \App\Models\Inventaris::observe(\App\Observers\LabActivityObserver::class);
        \App\Models\Lab\Pengadaan::observe(\App\Observers\LabActivityObserver::class);
        \App\Models\Lab\PinjamEksternal::observe(\App\Observers\LabActivityObserver::class);
    }
}
