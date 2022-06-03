<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalTransfer extends Model
{
    protected $fillable = [
        'sender_bank_id',
        'recipient_bank_id',
        'sender_card_number',
        'recipient_card_number',
        'value'
    ];

    use HasFactory;
}
