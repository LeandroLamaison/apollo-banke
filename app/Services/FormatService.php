<?php
namespace App\Services;

class FormatService {

    public static function hour (string $date) {
        $date = date_create($date);
        return date_format($date,"d/m/Y H:i:s");
    }

    public static function date (string $date) {
        $date = date_create($date);
        return date_format($date,"d/m/Y");
    }

    public static function month (string $date) {
        $date = date_create($date);
        return date_format($date,"m/Y");
    }

    public static function money (float $value) {
        return 'R$' . ' ' . number_format($value, 2, ',', '');
    }

    public static function cardNumber (int $cardNumber) {
        return implode('-', str_split($cardNumber, 4));
    }

    public static function transactionType (string $type) {
        if ($type == 'deposit') {
            return __('transaction.deposit');
        }

        if ($type == 'withdrawal') {
            return __('transaction.withdrawal');
        }

        return null;
    }
}

?>