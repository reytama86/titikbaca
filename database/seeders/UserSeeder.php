<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Buat pengguna biasa (opsional)
        User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('user'), // Gantilah password sesuai kebutuhan
            'level' => '0', // Pastikan kolom role sesuai dengan yang ada di tabel
        ]);
    }
}
