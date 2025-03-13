<?php
;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('can create a user', function () {
    $user = User::factory()->create();

    expect($user->id)->toBeString();
});

it('can update a user', function () {
    $user = User::factory()->create();

    $user->update([
        'name' => 'John',
        'email' => 'example@gmail.com',
        'password' => 'secret',
    ]);

    expect($user->name)->toEqual('John');
    expect($user->email)->toEqual('example@gmail.com');
    expect(Hash::check('secret', $user->password))->toBeTrue();
});

it('can delete a user', function () {
    $user = User::factory()->create();
    $user->delete();

    $this->assertDatabaseMissing($user->getTable(), ['id' => $user->id]);
});
