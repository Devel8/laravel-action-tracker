<?php

namespace Devel8\LaravelActionTracker;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class ActionTrackerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/../config/action-tracker.php' => config_path('action-tracker.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        Event::listen(ActionTracked::class, function (ActionTracker $actionTracker) {
            if(Config::get('action-tracker.log_tracking'))
                Log::info("{$actionTracker->action}: {$actionTracker->message}");
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/action-tracker.php', 'action-tracker'
        );
    }
}
