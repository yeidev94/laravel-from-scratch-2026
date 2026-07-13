<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123')
        ->click('@register-button')
        ->assertPathIs('/ideas');

    assertAuthenticated();

    $user = User::query()->findOrFail(Auth::id());

    expect($user->toArray())->toMatchArray([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

it('logs in a user', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    visit('/login')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123')
        ->click('@login-button')
        ->assertPathIs('/ideas');

    assertAuthenticated();
});

it('logs out a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')
        ->click('Log Out');

    assertGuest();
});

it('requires a valid email address', function () {
    visit('/register')
        ->fill('name', 'John')
        ->fill('password', 'password123')
        ->click('@register-button')
        ->assertPathIs('/register');
});
