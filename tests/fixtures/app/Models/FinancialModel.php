<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialModel extends Model
{
    protected $fillable = ['amount', 'currency', 'balance'];

    protected $casts = [
        'amount' => 'float',
        'balance' => 'decimal:2',
    ];
}
