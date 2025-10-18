<?php

    namespace App\Console\Commands;

    use App\Helpers\utitlities\Convert;
    use App\Models\Registration;
    use Illuminate\Console\Command;

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

            foreach ($registrations as $registration) {
                $registration->update([
                    'scholarship' => Convert::currencyToInt($registration->scholarship),
                ]);
            }

            $this->info('--- Fin de la mises a jour ---');
            return Command::SUCCESS;
        }
    }
