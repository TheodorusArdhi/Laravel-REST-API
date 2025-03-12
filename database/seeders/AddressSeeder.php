<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = Contact::query()->limit(1)->first();
        Address::create([
            'contact_id' => $contact->id,
            'street' => 'test street',
            'city' => 'test city',
            'province' => 'test province',
            'country' => 'test country',
            'postal_code' => '55678'
        ]);

        Address::create([
            'contact_id' => $contact->id,
            'street' => 'test street2',
            'city' => 'test city2',
            'province' => 'test province2',
            'country' => 'test country2',
            'postal_code' => '55679'
        ]);
    }
}
