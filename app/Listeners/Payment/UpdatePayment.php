<?php

    namespace App\Listeners\Payment;

    use App\Events\Registration\RegistrationUpdated;
    use App\Models\Payment;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class UpdatePayment
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
        public function handle(RegistrationUpdated $event): void
        {
            $payment = Payment::where('registration_id', $event->registration->id)->first();

            if (!$payment) {
                $payment = payment::create([
                    'registration_id' => $event->registration->id,
                    'paid' => '0 CFA',
                    'remain' => $event->registration->scholarship,
                ]);
            }
            else {
                $remain = intval($payment->remain);
                $paid = intval($payment->paid);
                $scholarship = intval($event->registration->scholarship);

                $remain = $scholarship - $paid;
                $paid = $scholarship - $remain;

                $payment->remain = $remain;
                $payment->paid = $paid;

                $payment->save();
            }
        }
    }
