<?php

namespace App\Listeners;

use App\Events\TopicReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreTopic
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
     * @param  TopicReceived  $event
     * @return void
     */
    public function handle(TopicReceived $event)
    {
        //
    }
}
