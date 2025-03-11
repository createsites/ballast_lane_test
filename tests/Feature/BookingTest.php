<?php

use App\Models\User;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('does not allow booking with more guests than table capacity', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create(['capacity' => 4]);

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/bookings', [
        'table_id' => $table->id,
        'booking_time' => now()->addDay(),
        'guest_count' => 6, // Grater than capacity of the table
    ]);

    $response->assertStatus(422); // Validation error
});

it('allows valid booking within table capacity', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create(['capacity' => 4]);

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/bookings', [
        'table_id' => $table->id,
        'booking_time' => now()->addDay(),
        'guest_count' => 4,
    ]);

    $response->assertStatus(201);
});

