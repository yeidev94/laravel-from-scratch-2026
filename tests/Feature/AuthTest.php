<?php

use App\Models\User;
use Pest\Browser\Browser;


it('register a user', function() {

    visit('/register')
        ->fill('name', 'Jane Doe')
        ->fill('email', 'janedoe@mail.com')
        ->fill('password', 'secret1234')
        ->press('@register-button')
        ->assertPathIs('/ideas');

    // expect(User::count())->toBe(1);

    // $this->assertAuthenticated();

    });