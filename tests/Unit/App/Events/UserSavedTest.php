<?php

namespace Tests\Unit\App\Events;

use Tests\TestCase;
use App\Models\User;
use App\Events\UserSaved;
use Illuminate\Support\Facades\Event;

class UserSavedTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    /** @test */
    public function it_should_save_user_detail_when_user_is_saved()
    {

        User::factory()->create();
        Event::assertDispatched(UserSaved::class);

    }
}