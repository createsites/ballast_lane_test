<?php

use App\Models\Booking;
use App\Models\User;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires auth for showing bookings', function () {
    $response = $this->getJson(route('bookings.index'));
    $response->assertStatus(401);
});

it('requires auth for showing the booking', function () {
    $response = $this->getJson(route('bookings.show', 'nothing'));
    $response->assertStatus(401);
});

it('requires auth for creating the booking', function () {
    $response = $this->postJson(route('bookings.store', 'nothing'));
    $response->assertStatus(401);
});

it('requires auth for updating the booking', function () {
    $response = $this->putJson(route('bookings.update', 'nothing'));
    $response->assertStatus(401);
});

it('requires auth for deleting the booking', function () {
    $response = $this->deleteJson(route('bookings.destroy', 'nothing'));
    $response->assertStatus(401);
});

it('can create a booking', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson(route('bookings.store'), [
        'table_id' => $table->id,
        'user_id' => $user->id,
    ]);

    $response->assertStatus(201);
});

it('can not create a booking with a non existing table', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson(route('bookings.store'), [
        'table_id' => 'not_exists',
        'user_id' => $user->id,
    ]);

    $response->assertStatus(422);
});

it('can retrieve all bookings of current user', function () {
    $user = User::factory()->create();
    Booking::factory()->count(3)->create(['user_id' => $user->id]);

    $this->actingAs($user, 'sanctum');

    $response = $this->getJson(route('bookings.index'));

    $response->assertStatus(200);
    expect($response->json())->toHaveCount(3);
});

it('can retrieve a booking of current user by uuid', function () {
    $user = User::factory()->create();
    $booking = Booking::factory(['user_id' => $user->id])->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->getJson(route('bookings.show', ['booking' => $booking->id]));

    $response->assertStatus(200);

    $response->assertJsonFragment([
        'id' => $booking->id,
    ]);
});

it('can not retrieve a booking of other user (forbidden)', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->getJson(route('bookings.show', ['booking' => $booking->id]));

    $response->assertStatus(403);
});

it('allows update a booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory(['user_id' => $user->id])->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->putJson(
        route('bookings.update', $booking), [
            'table_id' => $table->id,
        ]
    );

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'table_id' => $table->id,
    ]);
});

it('does not allow update a booking from other user', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->putJson(
        route('bookings.update', $booking), [
            'table_id' => $table->id,
        ]
    );

    $response->assertStatus(403);
});

it('does not allow update user_id of a booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory(['user_id' => $user->id])->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->putJson(
        route('bookings.update', $booking), [
            'user_id' => 'some_users_uuid',
       ]
    );

    $response->assertStatus(422);
});

it('allows delete a booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory(['user_id' => $user->id])->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson(
        route('bookings.destroy', $booking)
    );

    $response->assertStatus(204);
    $this->assertDatabaseMissing($booking->getTable(), ['id' => $booking->id]);
});

it('does not allow delete a booking from other user', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson(
        route('bookings.destroy', $booking)
    );

    $response->assertStatus(403);
});
