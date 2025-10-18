<?php

    namespace App\Console\Commands;

    use App\Helpers\utitlities\Convert;
    use App\Models\Configuration;
    use App\Models\Payment;
    use App\Models\Receipt;
    use App\Models\Registration;
    use App\Models\ScholarshipFeed;
    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\DB;
    use Throwable;

    class UpdateCurrencyToInt extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'app:update-currency-to-int';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Command description';

        /**
         * Execute the console command.
         */
        public function handle()
        {
            $this->info('--- Début de la mises à jour ---');

            $registrations = Registration::all();
            $payments = Payment::all();
            $receipts = Receipt::all();
            $scholarships = ScholarshipFeed::all();
            $configs = Configuration::all();

            try {
                DB::transaction(function () use ($registrations, $payments, $receipts, $scholarships, $configs) {
                    foreach ($registrations as $registration) {
                        $registration->update([
                            'scholarship' => Convert::currencyToInt($registration->scholarship),
                        ]);
                    }

                    foreach ($payments as $payment) {
                        $payment->update([
                            'paid' => Convert::currencyToInt($payment->paid),
                            'remain' => Convert::currencyToInt($payment->remain),
                        ]);
                    }

                    foreach ($receipts as $receipt) {
                        $receipt->update([
                            'amount' => Convert::currencyToInt($receipt->amount)
                        ]);
                    }

                    foreach ($scholarships as $scholarship) {
                        $scholarship->update([
                            'normal' => Convert::currencyToInt($scholarship->normal),
                            'subvention' => Convert::currencyToInt($scholarship->subvention),
                        ]);
                    }

                    foreach ($configs as $config) {
                        $config->update([
                            'registration_fees' => Convert::currencyToInt($config->registration_fees)
                        ]);
                    }
                });
            } catch (Throwable $e) {
                $this->info("Exception: " . $e->getMessage());
            }

            $this->info('--- Fin de la mises a jour ---');
            return Command::SUCCESS;
        }
    }
