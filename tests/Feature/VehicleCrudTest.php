<?php

use App\Models\Dealership;
use App\Models\User;
use App\Models\Vehicle;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    $this->dealership = Dealership::factory()->create();
    $this->user = User::factory()->create(['dealership_id' => $this->dealership->id]);
    actingAs($this->user);
});

it('creates a vehicle scoped to the user dealership', function () {
    post(route('vehicles.store'), [
        'year' => 2024, 'make' => 'Tesla', 'model' => 'Model 3', 'trim' => 'Long Range',
        'status' => 'draft',
    ])->assertRedirect();

    expect(Vehicle::count())->toBe(1);
    expect(Vehicle::first()->dealership_id)->toBe($this->dealership->id);
});

it('lists only the user\'s vehicles', function () {
    $mine = Vehicle::factory()->for($this->dealership)->create(['make' => 'Mine']);
    $other = Vehicle::factory()->for(Dealership::factory())->create(['make' => 'Other']);

    get(route('vehicles.index'))
        ->assertOk()
        ->assertSee('Mine')
        ->assertDontSee('Other');
});

it('allows editing own vehicle', function () {
    $v = Vehicle::factory()->for($this->dealership)->create();

    put(route('vehicles.update', $v), [
        'make' => 'Updated',
    ])->assertRedirect();

    expect($v->fresh()->make)->toBe('Updated');
});
