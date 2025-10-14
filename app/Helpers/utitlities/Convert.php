<?php

    namespace App\Helpers\utitlities;

    class Convert
    {
        static function intToCurrency($value): string
        {
            $result = self::currencyToInt($value);
            return number_format($result, decimals: 0, thousands_separator: ' ') . ' CFA';
        }

        static function currencyToInt($value): int
        {
            $result = preg_replace("/\D/", '', $value);
            return intval($result);
        }
    }
