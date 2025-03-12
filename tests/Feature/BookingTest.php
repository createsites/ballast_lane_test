<?php

use App\Models\Booking;
use App\Models\User;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a booking', function () {
    $booking = Booking::factory()->create();

    expect($booking->id)->toBeString();
    expect($booking->table->id)->toBeString();
});

it('can update a booking', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create();
    $booking = Booking::factory()->create();
    $now = now();

    $booking->update([
        'user_id' => $user->id,
        'table_id' => $table->id,
        'booking_time' => $now,
    ]);

    expect($booking->user_id)->toEqual($user->id);
    expect($booking->table_id)->toEqual($table->id);
    expect($booking->booking_time)->toEqual($now);
});

it('can delete a booking', function () {
    $booking = Booking::factory()->create();
    $booking->delete();

    $this->assertDatabaseMissing($booking->getTable(), ['id' => $booking->id]);
});
