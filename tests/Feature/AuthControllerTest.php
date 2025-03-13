<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can register', function () {
    $response = $this->postJson(route('register'), [
        'name' => 'tester',
        'email' => 'test@example.com',
        'password' => 'secretpass',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'access_token',
        'token_type',
    ]);
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

it('can login', function () {
    $credentials = [
        'email' => 'test@example.com',
        'password' => 'secretpass',
    ];
    User::factory($credentials)->create();
    $response = $this->postJson(route('login'), $credentials);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'access_token',
        'token_type',
    ]);
});

it('can logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');
    $response = $this->postJson(route('logout'));

    $response->assertStatus(200);
});

it('requires auth for logout page', function () {
    $response = $this->postJson(route('logout'));
    $response->assertStatus(401);
});

it('can show user profile', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');
    $response = $this->getJson(route('profile'));

    $response->assertStatus(200);
});

it('requires auth for profile page', function () {
    $response = $this->getJson(route('profile'));
    $response->assertStatus(401);
});
