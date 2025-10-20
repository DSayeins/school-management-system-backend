<?php

    namespace App\Events\Receipt;

    use Illuminate\Broadcasting\Channel;
    use Illuminate\Broadcasting\InteractsWithSockets;
    use Illuminate\Broadcasting\PresenceChannel;
    use Illuminate\Broadcasting\PrivateChannel;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class ReceiptDeleted
    {
        use Dispatchable, InteractsWithSockets, SerializesModels;

        /**
         * Create a new event instance.
         */
        public function __construct(public int $paymentId)
        {
            //
        }

        /**
         * Get the channels the event should broadcast on.
         *
         * @return array<int, Channel>
         */
        public function broadcastOn(): array
        {
            return [
                new PrivateChannel('channel-name'),
            ];
        }
    }
