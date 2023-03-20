<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\UserServiceInterface;

class SaveUserBackgroundInformation
{

    /** @var \App\Services\UserServiceInterface */
    protected $userService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->userService->saveDetails($event->user);
    }
}
