<?php

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires auth for showing tables', function () {
    $response = $this->getJson(route('tables.index'));
    $response->assertStatus(401);
});

it('requires auth for showing the table', function () {
    $response = $this->getJson(route('tables.show', 'nothing'));
    $response->assertStatus(401);
});

it('requires auth for creating the table', function () {
    $response = $this->postJson(route('tables.store', 'nothing'));
    $response->assertStatus(401);
});

it('requires auth for updating the table', function () {
    $response = $this->putJson(route('tables.update', 'nothing'));
    $response->assertStatus(401);
});

it('requires auth for deleting the table', function () {
    $response = $this->deleteJson(route('tables.destroy', 'nothing'));
    $response->assertStatus(401);
});

it('can create a table', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson(route('tables.store'), [
        'capacity' => 4,
        'location' => 'London',
    ]);

    $response->assertStatus(201);
});

it('can not create a table with too big capacity', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson(route('tables.store'), [
        'capacity' => 100,
        'location' => 'London',
    ]);

    $response->assertStatus(422);
});

it('can retrieve all tables', function () {
    $user = User::factory()->create();
    Table::factory()->count(3)->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->getJson(route('tables.index'));

    $response->assertStatus(200);
    expect($response->json())->toHaveCount(3);
});

it('can retrieve a table by uuid', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->getJson(route('tables.show', ['table' => $table->id]));

    $response->assertStatus(200);

    $response->assertJsonFragment([
        'id' => $table->id,
    ]);
});

it('allows update a table', function () {
    $user = User::factory()->create();
    $table = Table::factory(['location' => 'London'])->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->putJson(
        route('tables.update', $table), [
            'location' => 'Berlin',
        ]
    );

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'location' => 'Berlin',
    ]);
});


it('does not allow update a table with empty required fields', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->putJson(
        route('tables.update', $table), [
            'location' => '',
            'capacity' => '',
        ]
    );

    $response->assertStatus(422);
});

it('allows delete a table', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson(
        route('tables.destroy', $table)
    );

    $response->assertStatus(204);
    $this->assertDatabaseMissing($table->getTable(), ['id' => $table->id]);
});
