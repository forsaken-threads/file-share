<?php

namespace App\Listeners;

use App\User;
use Illuminate\Auth\Events\Registered;

class UserRegistered
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle($event)
    {
        \Mail::raw('User registered: ' . $event->user->toJson(), function($message) {
            $admin = User::first();
            $message->subject('User registration at ' . config('app.name'))
                ->to($admin->email);
        });
    }
}
