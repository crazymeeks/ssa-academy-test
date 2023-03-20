<?php


namespace Tests\Unit\App\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\UploadedFile as LaravelUploadedFile;

class UserServiceTest extends TestCase
{

    /** @var \App\Services\UserService */
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_can_store_a_user_to_database(array $data)
    {
        $user = $this->userService->store($data);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'prefixname' => $data['prefixname'],
            'suffixname' => $data['suffixname'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'type' => $data['type'],
        ]);
    }

    /**
     * @test
     */
    public function it_can_update_an_existing_user()
    {
        $user = User::factory()->create();

        $result = $this->userService->update($user->id, ['firstname' => 'Jane']);

        $this->assertInstanceOf(User::class, $result);

        $this->assertDatabaseHas('users', [
            'firstname' => 'Jane'
        ]);
    }

    /** @test */
    public function it_can_soft_delete_an_existing_user()
    {
        $user = User::factory()->create();

        $result = $this->userService->destroy($user->id);
        $this->assertTrue($result);
        
        $inTrashed = User::withTrashed()->whereId($user->id)->first();
        $notInTrashed = User::whereId($user->id)->first();
        $this->assertNotNull($inTrashed);
        $this->assertNull($notInTrashed);

    }

    /** @test */
    public function it_can_restore_a_soft_deleted_user()
    {
        $user = User::factory()->create();
        $user->delete();

        $result = $this->userService->restore($user->id);

        $this->assertTrue($result);

        $restored = User::find($user->id);
        $this->assertNotNull($restored);
    }

    /** @test */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        $user = User::factory()->create();
        $user->delete();

        $result = $this->userService->delete($user->id);

        $this->assertTrue($result);

        $restored = User::find($user->id);
        $this->assertNull($restored);
    }

    /** @test */
    public function it_can_upload_photo()
    {
        $file = LaravelUploadedFile::fake()->image('avatar.jpg');

        $result = $this->userService->upload($file);

        $this->assertTrue(str_contains($result, 'images'));
    }

    public function data()
    {
        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => 'dela Cruz',
            'lastname' => 'Doe',
            'suffixname' => NULL,
            'username' => 'jdoe',
            'email' => 'jdoe.example.com',
            'password' => 'password',
            'type' => 'user',
        ];

        return [
            array($data)
        ];
    }
}