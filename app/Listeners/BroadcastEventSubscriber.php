<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Events\Dispatcher;
use App\Events\Broadcast\BroadcastSaved;
use App\Events\Broadcast\BroadcastClosed;
use App\Events\Broadcast\BroadcastErrorFound;
use App\Events\Sermon\SermonSaved;
use App\Events\Sermon\SermonDeleted;
use App\Services\Broadcast\ConfigureBroadcast;
use App\Services\Broadcast\ConfigureAllBroadcasts;
use App\Services\Broadcast\DisableBroadcast;

class BroadcastEventSubscriber
{
    /**
     * @var ConfigureBroadcast
     */
    private $configureBroadcast;

    /**
     * @var ConfigureAllBroadcasts
     */
    private $configureAllBroadcasts;

    /**
     * @var DisableBroadcast
     */
    private $disableBroadcast;

    public function __construct(ConfigureBroadcast $configureBroadcast, ConfigureAllBroadcasts $configureAllBroadcasts, DisableBroadcast $disableBroadcast)
    {
        $this->configureBroadcast = $configureBroadcast;
        $this->configureAllBroadcasts = $configureAllBroadcasts;
        $this->disableBroadcast = $disableBroadcast;
    }

    public function onBroadcastSaved(BroadcastSaved $event) : void
    {   
        $this->configureBroadcast->execute($event->broadcast);
    }

    public function onBroadcastClosed(BroadcastClosed $event) : void
    {   
        // If the event is live and is not recurring we need
        // to disable the broadcast after it closes because
        // that means it was intended to be a one-time event.
        if ($event->broadcast->live && !$event->broadcast->recurring) {
            $this->disableBroadcast->execute($event->broadcast);
        } else {
            $this->configureBroadcast->execute($event->broadcast);
        }
    }

    public function onBroadcastErrorFound(BroadcastErrorFound $event) : void
    {   
        $this->configureBroadcast->execute($event->broadcast);
    }

    public function onSermonSaved(SermonSaved $event) : void
    {
        $this->configureAllBroadcasts->execute();
    }

    public function onSermonDeleted(SermonDeleted $event) : void
    {
        $this->configureAllBroadcasts->execute();
    }

    public function subscribe(Dispatcher $events) : void
    {
        $events->listen(
            'App\Events\Broadcast\BroadcastSaved',
            'App\Listeners\BroadcastEventSubscriber@onBroadcastSaved'
        );

        $events->listen(
            'App\Events\Broadcast\BroadcastClosed',
            'App\Listeners\BroadcastEventSubscriber@onBroadcastClosed'
        );

        $events->listen(
            'App\Events\Broadcast\BroadcastErrorFound',
            'App\Listeners\BroadcastEventSubscriber@onBroadcastErrorFound'
        );

        $events->listen(
            'App\Events\Sermon\SermonSaved',
            'App\Listeners\BroadcastEventSubscriber@onSermonSaved'
        );

        $events->listen(
            'App\Events\Sermon\SermonDeleted',
            'App\Listeners\BroadcastEventSubscriber@onSermonDeleted'
        );
    }
}
