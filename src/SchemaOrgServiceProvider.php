<?php

declare(strict_types=1);

namespace Seo\SchemaOrg\Laravel;

use Illuminate\Support\ServiceProvider;
use Seo\SchemaOrg\Laravel\Console\Commands;

final class SchemaOrgServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    private function bootForConsole(): void
    {
        $this->commands([
            Commands\InstallCommand::class,
        ]);
    }
}
