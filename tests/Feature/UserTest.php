<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'your_name',
            'password' => 'your_password',
            'name' => 'your_name last_name'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => 'your_name',
                    'name' => 'your_name last_name'
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ]
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'your_name',
            'password' => 'your_password',
            'name' => 'your_name last_name'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "username already registered"
                    ]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'your_username',
            'password' => 'your_password'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'your_username',
                    'name' => 'your_name',
                ]
            ]);

        $user = User::where('username', 'your_username')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'your_token'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'your_username',
                    'name' => 'your_name',
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'your_username')->first();

        $this->patch('/api/users/current',
            [
                'password' => 'your_password_update'
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'your_username',
                    'name' => 'your_name',
                ]
            ]);

        $newUser = User::where('username', 'your_username')->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'your_username')->first();

        $this->patch('/api/users/current',
            [
                'name' => 'your_name_update'
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'your_username',
                    'name' => 'your_name_update',
                ]
            ]);

        $newUser = User::where('username', 'your_username')->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->patch('/api/users/current',
            [
                'name' => 'your_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_updateyour_name_update'
            ],
            [
                'Authorization' => 'your_token'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        "The name field must not be greater than 100 characters."
                    ]
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'your_token'
        ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);

        $user = User::where('username', 'your_username')->first();
        self::assertNull($user->token);

    }

    public function testLogoutFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    }


}
