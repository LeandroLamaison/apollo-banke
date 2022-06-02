<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@apollo.com',
            'password' => Hash::make('WUsZ6kwdZ'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Artêmis Bank',
            'email' => 'admin@artemis.com',
            'password' => Hash::make('x7v02KMvQu'),
            'is_bank' => true,
        ]);
    }
}
