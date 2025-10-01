<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $tables = "roles";
    protected $fillable = ['id', 'nama_role'];

    public function user()
    {
        return $this->hasMany(User::class, 'id_role');
    }
}
