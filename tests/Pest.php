<?php

declare(strict_types=1);

use Seo\SchemaOrg\Graph;
use Seo\SchemaOrg\Laravel\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * @param Closure(Graph): void $configure
 */
function configureSchemaOrg(Closure $configure): void
{
    if (TestCase::$current instanceof TestCase) {
        TestCase::$current->configureSchemaOrgForTest($configure);

        return;
    }

    TestCase::configureSchemaOrg($configure);
}
