<?php

use function Pest\Laravel\get;

it('serves the home page', function () {
    get('/')->assertOk()->assertSee('HelloRamp');
});

it('serves product pages', function () {
    get('/studio')->assertOk();
    get('/social')->assertOk();
    get('/engage')->assertOk();
});

it('serves solutions, pricing, contact', function () {
    get('/solutions')->assertOk();
    get('/pricing')->assertOk();
    get('/contact')->assertOk();
});

it('serves case studies index and detail', function () {
    $study = \App\Models\CaseStudy::factory()->create(['is_published' => true]);
    get('/case-studies')->assertOk();
    get('/case-studies/'.$study->slug)->assertOk()->assertSee($study->headline);
});

it('404s unpublished case studies', function () {
    $study = \App\Models\CaseStudy::factory()->create(['is_published' => false]);
    get('/case-studies/'.$study->slug)->assertNotFound();
});
