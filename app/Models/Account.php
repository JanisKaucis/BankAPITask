<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'currency', 'balance'];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
