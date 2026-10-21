<?php

use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

test('login and forgot password render session alerts', function () {
    foreach (['login', 'password.request'] as $route) {
        $this->withSession(['status' => 'Reset link sent.'])->get(route($route))
            ->assertOk()->assertSee('alert alert-success', false)->assertSee('Reset link sent.');
    }
});

test('profile status messages use daisyui alerts', function () {
    $this->actingAs(User::factory()->unverified()->create());
    foreach (['profile-updated' => 'Profile updated.', 'password-updated' => 'Password updated.',
        'verification-link-sent' => 'A new verification link has been sent to your email address.'] as $status => $text) {
        $this->withSession(['status' => $status])->get(route('profile.edit'))
            ->assertOk()->assertSee('alert alert-success', false)->assertSee($text);
    }
});

test('profile forms render named errors and reopen the deletion dialog', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $errors = new ViewErrorBag();
    $errors->put('updatePassword', new MessageBag(['current_password' => 'Current password is wrong.']));
    $errors->put('userDeletion', new MessageBag(['password' => 'Deletion password is wrong.']));
    $this->view('profile.edit', ['user' => $user, 'errors' => $errors])
        ->assertSee('Current password is wrong.')->assertSee('Deletion password is wrong.')
        ->assertSee("<script>document.getElementById('confirm-user-deletion').showModal();</script>", false);

});

test('dashboard uses the shared layout and daisyui', function () {
    $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertOk()
        ->assertSee('alert alert-success', false)->assertSee('My Posts');
});
