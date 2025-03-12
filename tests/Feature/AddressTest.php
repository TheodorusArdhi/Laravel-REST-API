<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'street_address_test',
                'city' => 'city_address_test',
                'province' => 'province_address_test',
                'country' => 'country_address_test',
                'postal_code' => '11223',
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(201)
            ->assertJson([
                'data' => [
                    'street' => 'street_address_test',
                    'city' => 'city_address_test',
                    'province' => 'province_address_test',
                    'country' => 'country_address_test',
                    'postal_code' => '11223',
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'street_address_test',
                'city' => 'city_address_test',
                'province' => 'province_address_test',
                'country' => '',
                'postal_code' => '11223',
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'country' => ['The country field is required.']
                ]
            ]);
    }

    public function testCreateContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . ($contact->id + 1) . '/addresses',
            [
                'street' => 'street_address_test',
                'city' => 'city_address_test',
                'province' => 'province_address_test',
                'country' => 'country_address_test',
                'postal_code' => '11223',
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'Authorization' => 'your_token'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'test street',
                    'city' => 'test city',
                    'province' => 'test province',
                    'country' => 'test country',
                    'postal_code' => '55678'
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 2), [
            'Authorization' => 'your_token'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
            [
                'street' => 'street_address_update',
                'city' => 'city_address_update',
                'province' => 'province_address_update',
                'country' => 'country_address_update',
                'postal_code' => '11224',
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'street_address_update',
                    'city' => 'city_address_update',
                    'province' => 'province_address_update',
                    'country' => 'country_address_update',
                    'postal_code' => '11224',
                ]
            ]);

    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
            [
                'street' => 'street_address_update',
                'city' => 'city_address_update',
                'province' => 'province_address_update',
                'country' => '',
                'postal_code' => '11224',
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'country' => ['The country field is required.']
                ]
            ]);
    }

    public function testUpdateNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 2),
            [
                'street' => 'street_address_update',
                'city' => 'city_address_update',
                'province' => 'province_address_update',
                'country' => 'country_address_update',
                'postal_code' => '11224',
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
            [
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);

    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 2),
            [
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testListSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id . '/addresses',
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'street' => 'test street',
                        'city' => 'test city',
                        'province' => 'test province',
                        'country' => 'test country',
                        'postal_code' => '55678'
                    ],
                    [
                        'street' => 'test street2',
                        'city' => 'test city2',
                        'province' => 'test province2',
                        'country' => 'test country2',
                        'postal_code' => '55679'
                    ]
                ]
            ]);
    }

    public function testListContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . ($contact->id + 1) . '/addresses',
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }
}
