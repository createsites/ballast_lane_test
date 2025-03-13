<?php

use App\Models\Booking;
use App\Models\User;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires auth for the booking table', function () {
    $response = $this->postJson(route('bookings.store'));
    $response->assertStatus(401);
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

it('allows valid booking within table capacity', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create(['capacity' => 4]);

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson(route('bookings.store'), [
        'table_id' => $table->id,
    ]);

    $response->assertStatus(201);
});

it('allows update a booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory(['user_id' => $user->id])->create();
    $table = Table::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->putJson(
        route('bookings.update', $booking), [
            'table_id' => $table->id,
            'table_id' => $table->id,
        ]
    );

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'table_id' => $table->id,
    ]);
});
