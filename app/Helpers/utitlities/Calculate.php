<?php

    namespace App\Helpers\utitlities;

    class Calculate
    {
        public static function feed(array $data = []): int
        {
            $discount = floatval($data['discountValue'] / 100);
            $bourse = floatval($data['bourseValue'] / 100);

            $amount = Convert::currencyToInt($data['amount']);

            $regFeed = Convert::currencyToInt($data['registrationFees']);

            $tranche = $data['periods'];

            if ($data['discountState'] == 1) $scholarship = $amount - ($amount * $discount);
            else if ($data['bourseState'] == 1) $scholarship = $amount - ($amount * $bourse);
            else $scholarship = $amount;

            $scholarship = intval($scholarship);

            // cette partie doit etre revue, aucune valeur en dur ne doit intervenir dans le processus de calcul
            if ($tranche < 3) {
                $test = $data['includeRegistrationFees'] == 1 && ($data['discountState'] == 0 || $data['bourseState'] == 0);

                $part = $test ? ($scholarship - $regFeed) / 3 : $scholarship / 3;
                $scholarship = $test ? ($part * $tranche) + $regFeed : $part * $tranche;
            }

            return $scholarship;
        }
    }
