<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Tests;

use ConduitUI\Actions\ActionsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup environment
    }
}
