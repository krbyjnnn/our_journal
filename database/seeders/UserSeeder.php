<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create your account (Me)
        User::create([
            'name' => 'Me',
            'email' => 'me@journal.com',
            'password' => Hash::make('013004'), // Your passcode
        ]);

        // Create your girlfriend's account (My GF)
        User::create([
            'name' => 'Gf',
            'email' => 'gf@journal.com',
            'password' => Hash::make('030605'), // Her passcode
        ]);
    }
}