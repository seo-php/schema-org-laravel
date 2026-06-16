<?php

declare(strict_types=1);

namespace Seo\SchemaOrg\Laravel\Tests;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Seo\Head\Laravel\HeadServiceProvider;
use Seo\SchemaOrg\Graph;
use Seo\SchemaOrg\Laravel\Providers\SchemaOrgServiceProvider as AppSchemaOrgServiceProvider;
use Seo\SchemaOrg\Laravel\SchemaOrgServiceProvider;

/**
 * @method \Illuminate\Testing\PendingCommand artisan(string $command, array<string, mixed> $parameters = [])
 */
abstract class TestCase extends OrchestraTestCase
{
    public static ?self $current = null;

    /**
     * @var ?Closure(Graph): void
     */
    public static ?Closure $configureUsing = null;

    /**
     * @param Closure(Graph): void $configure
     */
    final public static function configureSchemaOrg(Closure $configure): void
    {
        self::$configureUsing = $configure;
    }

    /**
     * @param Closure(Graph): void $configure
     */
    final public function configureSchemaOrgForTest(Closure $configure): void
    {
        self::$configureUsing = $configure;
        $this->refreshApplication();
    }

    protected function setUp(): void
    {
        self::$current = $this;

        parent::setUp();
    }

    protected function tearDown(): void
    {
        self::$configureUsing = null;
        self::$current = null;

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            HeadServiceProvider::class,
            SchemaOrgServiceProvider::class,
            TestSchemaOrgServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        /** @var Repository $config */
        $config = $app->make('config');
        $config->set('app.url', 'https://example.com');
    }
}

final class TestSchemaOrgServiceProvider extends AppSchemaOrgServiceProvider
{
    protected function configure(Graph $graph): void
    {
        if (TestCase::$configureUsing !== null) {
            (TestCase::$configureUsing)($graph);
        }
    }
}
