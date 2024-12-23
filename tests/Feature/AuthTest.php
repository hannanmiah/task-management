<?php

use App\Models\User;

beforeEach(function (){
    $this->user = User::factory()->create();

    $this->payload = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];
});
test('register', function () {
    $response = $this->postJson(route('auth.register'),$this->payload);
    $response->assertStatus(201);
});

test('login',function (){
    $payload = [
        'email' => $this->user->email,
        'password' => 'password'
    ];
    $response = $this->postJson(route('auth.login'),$payload);
    $response->assertStatus(200);
    $response->assertJsonStructure(['token']);
});

test('logout',function (){
    $response = $this->actingAs($this->user)->postJson(route('auth.logout'));
    $response->assertStatus(204);
});

test('login failed',function (){
    $payload = [
        'email' => $this->user->email,
        'password' => 'wrong'
    ];
    $response = $this->postJson(route('auth.login'),$payload);
    $response->assertStatus(401);
});


test('register failed',function (){
    $payload = [
        'email' => 'invalid',
        'password' => '235'
    ];
    $response = $this->postJson(route('auth.register'),$payload);
    $response->assertStatus(422);
});

test('logout failed',function (){
    $response = $this->postJson(route('auth.logout'));
    $response->assertStatus(401);
});
