<?php

use function Pest\Laravel\post;

it('accepts a valid contact submission', function () {
    post(route('contact.store'), [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'dealership' => 'Jane Motors',
        'message' => 'Please get in touch about pricing.',
    ])
        ->assertRedirect(route('contact.show'))
        ->assertSessionHas('status');
});

it('rejects an incomplete contact submission', function () {
    post(route('contact.store'), ['name' => '', 'email' => 'not-email', 'message' => 'x'])
        ->assertSessionHasErrors(['name', 'email', 'message']);
});
