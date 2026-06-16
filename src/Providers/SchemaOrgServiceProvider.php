<?php

declare(strict_types=1);

namespace Seo\SchemaOrg\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Seo\Head\Head;
use Seo\SchemaOrg\Graph;
use Seo\SchemaOrg\Head\Plugins\SchemaOrgPlugin;

abstract class SchemaOrgServiceProvider extends ServiceProvider
{
    final public function register(): void
    {
        $this->app->scopedIf(Graph::class, function () {
            $graph = new Graph();

            $this->configure($graph);

            return $graph;
        });

        $this->app->afterResolving(Head::class, function (Head $head): void {
            $head->plugin(SchemaOrgPlugin::make($this->app->make(Graph::class)));
        });
    }

    abstract protected function configure(Graph $graph): void;
}
