<?php

declare(strict_types=1);

namespace App\Services\Broadcast;

use App\Services\Broadcast\ConfigureBroadcast;
use App\Broadcast;

class ConfigureAllBroadcasts 
{
    /**
     * @var array
     */
	protected $configureBroadcast;

	public function __construct(ConfigureBroadcast $configureBroadcast)
	{
		$this->configureBroadcast = $configureBroadcast;
	}

    public function execute() : void
    {
    	$broadcasts = Broadcast::where('enabled', 1)->get();

    	foreach ($broadcasts as $broadcast) {
    		$this->configureBroadcast->execute($broadcast);
    	}
    }
}
