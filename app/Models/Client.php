<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'cpf', 'cellphone', 'birth_date', 'city', 'uf', 'user_id'];

    use HasFactory;
}
