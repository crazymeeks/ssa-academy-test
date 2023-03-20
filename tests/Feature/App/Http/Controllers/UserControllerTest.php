<?php


namespace Tests\Feature\App\Http\Controllers;


use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile as LaravelUploadedFile;

class UserControllerTest extends TestCase
{


    /**
     * @test
     * @dataProvider data
     */
    public function it_should_create_user(array $data)
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->post('/users', $data);
        
        $this->assertEquals('User successfully created!', $response->original['message']);
        $this->assertDatabaseHas('users', [
            'username' => 'username'
        ]);
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_should_update_existing_user(array $data)
    {
        $user = User::factory()->create();

        $data['id'] = $user->id;

        $response = $this->actingAs($user)
                         ->put('/users', $data);
        $this->assertEquals('User successfully updated!', $response->original['message']);

    }

    /** @test */
    public function it_should_soft_delete_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create([
            'email' => 'email2@example.com',
            'username' => 'username2',
        ]);

        $data['id'] = $user2->id;

        $response = $this->actingAs($user)
                         ->delete('/users', $data);
        $this->assertEquals('User successfully deleted!', $response->original['message']);
        
        $user = User::find($user2->id);
        $this->assertNull($user);

    }

    /** @test */
    public function it_should_permanently_delete_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create([
            'email' => 'email2@example.com',
            'username' => 'username2',
            'deleted_at' => now(),
        ]);

        $data['id'] = $user2->id;

        $response = $this->actingAs($user)
                         ->delete('/users/permanent', $data);
        $this->assertEquals('User permanently deleted!', $response->original['message']);
        
        $user = User::find($user2->id);
        $this->assertNull($user);
    }

    /** @test */
    public function it_should_restore_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create([
            'email' => 'email2@example.com',
            'username' => 'username2',
            'deleted_at' => now(),
        ]);

        $data['id'] = $user2->id;

        $response = $this->actingAs($user)
                         ->post('/users/restore', $data);
        $this->assertEquals('User successfully restored!', $response->original['message']);

        $restored = User::find($user2->id);
        $this->assertNotNull($restored);
    }

    /** @test */
    public function it_should_upload_photo()
    {
        $user = User::factory()->create();

        $data = [
            'file' => LaravelUploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->actingAs($user)
                         ->post('/users/upload', $data);

        $this->assertEquals('User photo successfully uploaded!', $response->original['message']);

        $user = User::first();

        $this->assertNotNull($user->photo);
    }

    /** @test */
    public function it_should_get_paginated_list_of_users()
    {
        for($a = 0; $a < 10; $a++){
            User::factory()->create([
                'username' => "username$a",
                'email' => "email$a@example.com"
            ]);
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->get('/users');

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response->original);
        
    }

    /** @test */
    public function it_should_find_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->get('/users/' . $user->id);

        $this->assertInstanceOf(User::class, $response->original);
    }

    /** @test */
    public function it_should_get_paginated_list_of_trashed_users()
    {
        for($a = 0; $a < 10; $a++){
            User::factory()->create([
                'username' => "username$a",
                'email' => "email$a@example.com",
                'deleted_at' => now(),
            ]);
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->get('/users/trashed');

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $response->original);
        
    }

    public function data()
    {
        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => 'dela Cruz',
            'lastname' => 'Doe',
            'suffixname' => NULL,
            'username' => 'username',
            'email' => 'jdoe@example.com',
            'password' => 'password',
            'type' => 'user',
        ];

        return [
            array($data)
        ];
    }
}