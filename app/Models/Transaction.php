<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'amount', 'type', 'description'];

    const TYPES = [
        1 => 'credit',
        2 => 'debit',
    ];
}
