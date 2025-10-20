<?php

    namespace App\Listeners\Receipt;

    use App\Events\Receipt\ReceiptDeleted;
    use App\Events\Registration\RegistrationDeleted;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class DeleteReceipts
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
            $payment = $event->payment;
            $payment->receipts()->delete();
            event(new ReceiptDeleted($payment->id));
        }
    }
