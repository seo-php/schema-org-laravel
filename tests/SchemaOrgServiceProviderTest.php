<?php

declare(strict_types=1);

use Seo\Head\Head;
use Seo\SchemaOrg\Graph;
use Seo\SchemaOrg\Types\Organization;

it('renders the graph through head', function (): void {
    configureSchemaOrg(function (Graph $graph): void {
        $graph->add(Organization::make()->name('Configured Org'));
    });

    app(Graph::class)->add(Organization::make()->name('Request Org'));

    expect(app(Head::class)->render()['headTags'])
        ->toContain('type="application/ld+json"')
        ->toContain('Configured Org')
        ->toContain('Request Org')
    ;
});

it('renders the graph of the container that resolves head', function (): void {
    // Octane resolves Head and Graph on a per-request clone of the application.
    $sandbox = clone app();

    $sandbox->make(Graph::class)->add(Organization::make()->name('Sandbox Org'));

    expect($sandbox->make(Head::class)->render()['headTags'])->toContain('Sandbox Org');
});
