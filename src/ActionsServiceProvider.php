<?php

declare(strict_types=1);

namespace ConduitUI\Action;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ActionsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('github-actions')
            ->hasConfigFile();
    }
}
