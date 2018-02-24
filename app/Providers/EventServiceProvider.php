<?php

namespace App\Providers;

use App\File;
use App\Listeners\UserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            UserRegistered::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        File::creating(function($file) {
            do {
                $name = str_random(8);

            } while (File::where('name', $name)->first());

            $file->name = $name;
        });
    }
}
