<?php 
namespace App\Services;

class AccountService {
    static private function createCardNumber (string $cpf) {
        $cardPrefix = config('constants.apollo.card_prefix');
        $part1 = substr($cpf, 0, 3);
        $part2 = substr($cpf, 3, 3);
        $part3 = substr($cpf, 6, 3);
        $part4 = substr($cpf, 9, 2);
        return $cardPrefix . $part1 + 1000 . $part2 + 2000 . $part3 + $part4 + 3000;
    }
        
    static private function createSecurityCode () {
        return rand(0, 9) . rand(0, 9) . rand(0, 9);
    }

    static private function createCreationDate () {
        $timestamp = time();
        return date('Y-m-d', $timestamp);
    }
        
    static private function createDueDate () {
        $timestamp = strtotime('+2 years');
        return date('Y-m-d', $timestamp);
    }

    static public function balance(float $oldBalance, float $value, string $transactionType) {
        if($transactionType === 'deposit') {
            return $oldBalance + $value;
        }

        if($transactionType === 'withdrawal') {
            return $oldBalance - $value;
        }

        return $oldBalance;
    }

    static public function create (string $cpf) {
        return [
            'card_number' => AccountService::createCardNumber($cpf),
            'security_code' => AccountService::createSecurityCode(),
            'creation_date' => AccountService::createCreationDate(),
            'due_date' => AccountService::createDueDate(),
            'balance' => 0
        ];
    }

    static public function format (object $account) {
        return [
           'card_number' => FormatService::cardNumber($account['card_number']),
           'security_code' => $account['security_code'],
           'creation_date' => FormatService::month($account['creation_date']),
           'due_date' => FormatService::month($account['due_date']),
           'balance' => $account['balance']
        ];
    }
}
?>