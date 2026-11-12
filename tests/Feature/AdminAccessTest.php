<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('blocks non-admins from the admin area', function () {
    $member = User::factory()->create(['role' => 'member']);
    actingAs($member);

    get('/admin')->assertForbidden();
    get('/admin/users')->assertForbidden();
    get('/admin/case-studies')->assertForbidden();
});

it('lets admins into the admin area', function () {
    $admin = User::factory()->admin()->create();
    actingAs($admin);

    get('/admin')->assertOk();
    get('/admin/users')->assertOk();
    get('/admin/case-studies')->assertOk();
    get('/admin/plans')->assertOk();
    get('/admin/review-queue')->assertOk();
});

it('redirects guests to login', function () {
    get('/admin')->assertRedirect('/login');
    get('/dashboard')->assertRedirect('/login');
});
