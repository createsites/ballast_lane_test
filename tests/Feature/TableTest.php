<?php

use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a table', function () {
    $table = Table::factory()->create([
        'capacity' => 4,
        'location' => 'Window',
    ]);

    expect($table->id)->toBeGreaterThan(0);
    expect($table->capacity)->toBe(4);
    expect($table->location)->toBe('Window');
});

it('can update a table', function () {
    $table = Table::factory()->create([
        'capacity' => 4,
        'location' => 'Window',
    ]);

    $table->update([
        'capacity' => 6,
        'location' => 'Center',
    ]);

    $this->assertEquals(6, $table->capacity);
    $this->assertEquals('Center', $table->location);
});

it('can delete a table', function () {
    $table = Table::factory()->create();

    $table->delete();

    $this->assertDatabaseMissing('tables', ['id' => $table->id]);
});
