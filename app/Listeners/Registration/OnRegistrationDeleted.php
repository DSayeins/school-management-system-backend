<?php

    namespace App\Listeners\Registration;

    use App\Events\Registration\RegistrationDeleted;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Support\Facades\DB;

    class OnRegistrationDeleted
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
        public function handle(RegistrationDeleted $event): void
        {
            $registration = $event->registration;
            $payment = $registration->payment;

            DB::transaction(function () use ($registration, $payment) {
                $payment->receipts()->delete();
                $payment->delete();
                $registration->delete();
            });
        }
    }
