<?php

    namespace App\Listeners\Payment;

    use App\Events\Registration\RegistrationStored;
    use App\Models\Payment;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class CreatePayment
    {
        /**
         * Create the event listener.
         */
        public function __construct()
        {
            //
        }

        /**
         * Handle the event.
         */
        public function handle(RegistrationStored $event): void
        {
            Payment::create([
                'registration_id' => $event->registration->id,
                'paid' => 0,
                'remain' => $event->registration->scholarship
            ]);
        }
    }
