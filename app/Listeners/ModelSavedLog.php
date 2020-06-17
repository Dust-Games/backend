<?php

namespace App\Listeners;

use App\Events\LeagueSaved;
use Illuminate\Support\Facades\Log;

class ModelSavedLog
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
     * @param  LeagueSaved  $event
     * @return void
     */
    public function handle(LeagueSaved $event)
    {
        Log::info('League was saved', $event->getModel()->toArray());
    }
}
