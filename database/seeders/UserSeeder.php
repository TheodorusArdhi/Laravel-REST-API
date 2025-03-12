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
            'username' => 'your_username',
            'password' => Hash::make('your_password'),
            'name' => 'your_name',
            'token' => 'your_token'
        ]);

        User::create([
            'username' => 'your_username2',
            'password' => Hash::make('your_password2'),
            'name' => 'your_name2',
            'token' => 'your_token2'
        ]);
    }
}
