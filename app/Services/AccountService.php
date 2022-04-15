<?php 
namespace App\Services;

class AccountService {
    static  private function createCardNumber (string $cpf) {
        $cardPrefix = config('constants.apollo.card_prefix');
        $part1 = substr($cpf, 0, 3);
        $part2 = substr($cpf, 3, 3);
        $part3 = substr($cpf, 6, 3);
        return $cardPrefix . $part1 + 1000 . $part2 + 2000 . $part3 + 3000;
    }
        
    static private function createSecurityCode () {
        return rand(0, 9) . rand(0, 9) . rand(0, 9);
    }
        
    static private function createDueDate () {
        $timestamp = strtotime('+2 years');
        return date('d-m-y', $timestamp);
    }

    static public function create (string $cpf) {
        return [
            'card_number' => AccountService::createCardNumber($cpf),
            'security_code' => AccountService::createSecurityCode(),
            'due_date' => AccountService::createDueDate()
        ];
    }
}
?>