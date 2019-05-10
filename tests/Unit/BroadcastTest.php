<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Broadcast;
use Carbon\Carbon;

class BroadcastTest extends TestCase
{
    /**
     * Test if broadcast is open.
     *
     * @return void
     */
    public function testBroadcastOpen()
    {
    	$now = Carbon::now();

    	$broadcast = Broadcast::find(1);

    	$broadcast->opens_at = $now;
    	$broadcast->starts_at = $now->copy()->addMinutes(10);
    	$broadcast->closes_at = $now->copy()->addMinutes(60);

        $this->assertEquals($broadcast->getBroadcastStatus(), 'broadcast_open');
    }

    /**
     * Test if broadcast in progress.
     *
     * @return void
     */
    public function testBroadcastInProgress()
    {
    	$now = Carbon::now();

    	$broadcast = Broadcast::find(1);

    	$broadcast->opens_at = $now->copy()->subMinutes(10);
    	$broadcast->starts_at = $now;
    	$broadcast->closes_at = $now->copy()->addMinutes(50);
    	
        $this->assertEquals($broadcast->getBroadcastStatus(), 'broadcast_in_progress');
    }

    /**
     * Test if broadcast closed and starts in the future.
     *
     * @return void
     */
    public function testBroadcastClosedFuture()
    {
    	$future = Carbon::now()->addSecond();

    	$broadcast = Broadcast::find(1);

    	$broadcast->opens_at = $future;
    	$broadcast->starts_at = $future->copy()->addMinutes(10);
    	$broadcast->closes_at = $future->copy()->addMinutes(50);

        $this->assertEquals($broadcast->getBroadcastStatus(), 'broadcast_closed');
    }

     /**
     * Test if broadcast in progress.
     *
     * @return void
     */
    public function testBroadcastClosedPast()
    {
    	$past = Carbon::now()->subMinutes(80);

    	$broadcast = Broadcast::find(1);

    	$broadcast->opens_at = $past;
    	$broadcast->starts_at = $past->copy()->addMinutes(10);
    	$broadcast->closes_at = $past->copy()->addMinutes(50);

        $this->assertEquals($broadcast->getBroadcastStatus(), 'broadcast_closed');
    }

     /**
     * Test if broadcast in progress.
     *
     * @return void
     */
    public function testBroadcastRepeatsWeekly()
    {
        $broadcast = Broadcast::find(1);

        $broadcast->repeats_weekly = 1;

        $this->assertTrue($broadcast->repeatsWeekly(), 'This message repeats weekly');

        $broadcast->repeats_weekly = 0;

        $this->assertFalse($broadcast->repeatsWeekly(), 'This message does not repeat weekly.');
    }
}
