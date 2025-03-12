<?php

use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a table', function () {
    $table = Table::factory()->create([
        'capacity' => 4,
        'location' => 'London',
    ]);

    expect($table->id)->toBeString();
    expect($table->capacity)->toBe(4);
    expect($table->location)->toBe('London');
});

it('can update a table', function () {
    $table = Table::factory()->create([
        'capacity' => 4,
        'location' => 'London',
    ]);

    $table->update([
        'capacity' => 6,
        'location' => 'Los Angeles',
    ]);

    $this->assertEquals(6, $table->capacity);
    $this->assertEquals('Los Angeles', $table->location);
});

it('can delete a table', function () {
    $table = Table::factory()->create();
    $table->delete();

    $this->assertDatabaseMissing($table->getTable(), ['id' => $table->id]);
});
