<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalTransfer extends Model
{
    protected $fillable = ['sender_account_id', 'recipient_account_id', 'value'];

    use HasFactory;
}
