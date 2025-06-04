<?php

use App\Models\User;

it('denies access for non admin', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/admin')
        ->assertStatus(403);
});

it('allows access for admin', function () {
    $user = User::factory()->create(['role' => 'admin']);

    actingAs($user)
        ->get('/admin')
        ->assertOk();
});

it('can create category', function () {
    $user = User::factory()->create(['role' => 'admin']);

    actingAs($user)
        ->post('/admin/categories', ['title' => 'Tech'])
        ->assertRedirect('/admin/categories');

    expect(\App\Models\Category::where('title', 'Tech')->exists())->toBeTrue();
});
