<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Payment\Entities\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OrderCreatedEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var Payment
     */
    public $payment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
