<?php
namespace App\Services;

class HistoryService {
    static private function getInternalTransferType (int $accountId, object $transfer) {
        if ($accountId === $transfer->sender_account_id) {
            return 'withdrawal';
        }

        if ($accountId === $transfer->recipient_account_id) {
            return 'deposit';
        }

        return null;
    }

    static private function getExternalTransferType (int $bankId, ?string $cardNumber, object $transfer) {
        if (
            $bankId === $transfer->sender_bank_id && 
            ($cardNumber === null || $cardNumber === $transfer->sender_card_number)
        ) {
            return 'withdrawal';
        }

        if (
            $bankId === $transfer->receiver_bank_id && 
            ($cardNumber === null || $cardNumber === $transfer->recipient_card_number)
        ) {
            return 'deposit';
        }

        return null;
    }


    static private function internalTransferToTransaction (int $accountId, object $transfers) {
        $transactions = [];

        for ($i=0; $i < count($transfers); $i++) {     
            $transactions[$i] = [
              'type' => HistoryService::getInternalTransferType($accountId, $transfers[$i]),
              'value' => $transfers[$i]->value,
              'created_at' => $transfers[$i]->created_at
            ];
        }

        return $transactions;
    }

    static private function externalTransferToTransaction (int $bankId, ?string $cardNumber, object $transfers) {
        $transactions = [];

        for ($i=0; $i < count($transfers); $i++) {     
            $transactions[$i] = [
              'type' => HistoryService::getExternalTransferType($bankId, $cardNumber, $transfers[$i]),
              'value' => $transfers[$i]->value,
              'created_at' => $transfers[$i]->created_at
            ];
        }

        return $transactions;
    }

    static private function parseTransactions (array $transactions) {
        $parsedTransactions = [];

        for ($i=0; $i < count($transactions); $i++) {     
            $parsedTransactions[$i] = [
                'type' => $transactions[$i]['type'],
                'value' => $transactions[$i]['value'],
                'created_at' => $transactions[$i]['created_at'],

                'type_text' => FormatService::transactionType($transactions[$i]['type']),
                'value_text' => FormatService::money($transactions[$i]['value']),
                'created_at_text' => FormatService::hour($transactions[$i]['created_at'])
            ];
        }

        return $parsedTransactions;
    }

    static public function buildHistory (
        object $transactions , 
        ?object $internalTransfers, 
        ?object $externalTransfers, 
        ?object $account,
        ?int $bankId
    ) {
        $internalTransferTransactions = [];
        $externalTransferTransactions = [];

        if($account && $internalTransfers) {
            $internalTransferTransactions = HistoryService::internalTransferToTransaction(
                $account['id'], 
                $internalTransfers
            );
        }

        if($bankId && $externalTransfers) {
            $externalTransferTransactions = HistoryService::externalTransferToTransaction(
                $bankId,
                $account ? $account['card_number'] : null,
                $externalTransfers
            );
        }

        dd($externalTransfers, $account, $bankId);
        $transactions = json_decode(json_encode($transactions), true);

        $history = array_merge($transactions, $internalTransferTransactions, $externalTransferTransactions);
        
        usort($history, function($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        return HistoryService::parseTransactions($history);
    }
}

?>