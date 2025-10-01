<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashDetail extends Model
{
    use HasFactory;

    protected $tables = "petty_cash_details";
    protected $fillable = [
        'pettycash_id',
        'kode_pettycash_details',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'note'
    ];

    public function pettycash()
    {
        return $this->belongsTo(pettycash::class, 'kode_pettycash');
    }
}
