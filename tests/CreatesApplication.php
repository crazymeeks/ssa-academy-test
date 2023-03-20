<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->runMigration();
        
        return $app;
    }

    private function runMigration()
    {
        Artisan::call('migrate');
        // Artisan::call('migrate', ['--path' => 'database/testing']); // if migrations for testing is specified. uncomment this
        Hash::setRounds(4);
    }
}
