<?php
namespace App\Services;

class FormatService {
    public static function date (string $date) {
        $date = date_create($date);
        return date_format($date,"d/m/Y");
    }

    public static function month (string $date) {
        $date = date_create($date);
        return date_format($date,"m/Y");
    }

    public static function cardNumber (int $cardNumber) {
        return implode('-', str_split($cardNumber, 4));
    }
}

?>