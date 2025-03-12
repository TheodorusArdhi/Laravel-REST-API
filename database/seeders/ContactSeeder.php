<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'your_username')->first();
        Contact::create([
            'first_name' => 'test first_name',
            'last_name' => 'test last_name',
            'email' => 'test_email@gmail.com',
            'phone' => '08111111',
            'user_id' => $user->id
        ]);
    }
}
