<?php
namespace App\Services;

class HistoryService {
    static private function getTransferType (int $accountId, object $transfer) {
        if ($accountId === $transfer->sender_account_id) {
            return 'withdraw';
        }

        if ($accountId === $transfer->recipient_account_id) {
            return 'deposit';
        }

        return null;
    }

    static public function transferToTransaction (int $accountId, object $transfers) {
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
}

?>