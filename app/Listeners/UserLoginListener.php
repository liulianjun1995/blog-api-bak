<?php

namespace App\Listeners;

use App\Events\UserLoginEvent;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoginEvent  $event
     * @return void
     */
    public function handle(UserLoginEvent $event)
    {
        $user = $event->user;
        $request = $event->request;

        $user->last_login_time = Carbon::now();
        $user->last_login_ip = $request->ip();

        $user->save();
    }
}
