<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $storagePath = storage_path('testing-storage');
        $this->app->useStoragePath($storagePath);

        foreach ([
            'app',
            'framework/cache',
            'framework/sessions',
            'framework/testing',
            'framework/views',
            'logs',
        ] as $directory) {
            File::ensureDirectoryExists($storagePath.'/'.$directory, 0777, true);
        }
    }
}
