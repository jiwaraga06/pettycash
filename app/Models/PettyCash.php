<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;

    protected $tables = "petty_cashes";
    protected $fillable = [
        'kode_pettycash',
        'amount',
        'used_amount',
        'description',
        'tipe',
        'status',
        'created_by',
        'dept_approved_by',
        'finance_approved_by',
        'tanggal_pencairan',
        'tanggal_pengajuan',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(PettyCashDetail::class, 'kode_pettycash_details');
    }
}
