<?php
namespace App\Services;

class HistoryService {
    static private function getTransferType (int $accountId, object $transfer) {
        if ($accountId === $transfer->sender_account_id) {
            return 'withdrawal';
        }

        if ($accountId === $transfer->recipient_account_id) {
            return 'deposit';
        }

        return null;
    }

    static private function transferToTransaction (int $accountId, object $transfers) {
        $transactions = [];

        for ($i=0; $i < count($transfers); $i++) {     
            $transactions[$i] = [
              'type' => HistoryService::getTransferType($accountId, $transfers[$i]),
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

    static public function buildHistory (object $transactions , ?object $transfers, ?int $accountId) {
        $transferTransactions = [];

        if ($transfers && $accountId) {
           $transferTransactions = HistoryService::transferToTransaction($accountId, $transfers);
        }

        $transactions = json_decode(json_encode($transactions), true);

        $history = array_merge($transferTransactions, $transactions);
        usort($history, function($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        echo json_encode($history);

        return HistoryService::parseTransactions($history);
    }
}

?>