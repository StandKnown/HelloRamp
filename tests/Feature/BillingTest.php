<?php

use App\Models\Plan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('renders the billing page', function () {
    actingAs(User::factory()->create());
    Plan::factory()->create(['name' => 'Starter']);

    get(route('billing.index'))
        ->assertOk()
        ->assertSee('Starter');
});

it('rejects subscription when plan has no stripe price id', function () {
    actingAs(User::factory()->create());
    $plan = Plan::factory()->create(['stripe_price_id' => null]);

    from(route('billing.index'))
        ->post(route('billing.subscribe', $plan))
        ->assertRedirect(route('billing.index'))
        ->assertSessionHasErrors('plan');
});
