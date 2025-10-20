<?php

    namespace App\Listeners\Payment;

    use App\Events\Receipt\ReceiptDeleted;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;

    class DeletePayment
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
        public function handle(ReceiptDeleted $event): void
        {
            //
        }
    }
