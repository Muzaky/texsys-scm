<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    
    public function run(): void
    {
        // Hapus user dengan email yang sama jika ada untuk menghindari error
        User::where('email', 'admin@example.com')->delete();

        // Buat user baru
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'), // Password di-hash demi keamanan
        ]);
    }
}
