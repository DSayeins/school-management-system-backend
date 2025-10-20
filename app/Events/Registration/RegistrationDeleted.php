<?php

    namespace App\Events\Registration;

    use App\Models\Registration;
    use Illuminate\Broadcasting\Channel;
    use Illuminate\Broadcasting\InteractsWithSockets;
    use Illuminate\Broadcasting\PresenceChannel;
    use Illuminate\Broadcasting\PrivateChannel;
    use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class RegistrationDeleted
    {
        use Dispatchable, InteractsWithSockets, SerializesModels;

        /**
         * Create a new event instance.
         */
        public function __construct(public Registration $registration)
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
