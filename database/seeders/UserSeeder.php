<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('password'),
            'id_role' => 1,
        ]);
        User::create([
            'name' => 'Atasan Departemen',
            'email' => 'atasan.dept@email.com',
            'password' => Hash::make('password'),
            'id_role' => 2,
        ]);
        User::create([
            'name' => 'Atasan Keuangan',
            'email' => 'atasan.fin@email.com',
            'password' => Hash::make('password'),
            'id_role' => 3,
        ]);
    }
}
