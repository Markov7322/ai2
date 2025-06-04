<?php

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

// Guest cannot access messages
it('guest cannot access messages', function () {
    $this->get('/messages')->assertRedirect('/login');
});

// User can start new conversation
it('user can start new conversation', function () {
    $u1 = User::factory()->create();
    $u2 = User::factory()->create();

    actingAs($u1)
        ->post(route('messages.start', $u2))
        ->assertRedirect();

    $this->assertDatabaseHas('conversations', [
        'user_one_id' => $u1->id,
        'user_two_id' => $u2->id,
    ]);
});

// User can send message
it('user can send message', function () {
    $u1 = User::factory()->create();
    $u2 = User::factory()->create();
    $conv = Conversation::create(['user_one_id' => $u1->id, 'user_two_id' => $u2->id]);

    actingAs($u1)
        ->post(route('messages.store', $conv), ['message' => 'hello'])
        ->assertRedirect();

    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conv->id,
        'sender_id' => $u1->id,
        'message' => 'hello',
    ]);
});

// User cannot view other conversation
it('user cannot view other conversation', function () {
    $u1 = User::factory()->create();
    $u2 = User::factory()->create();
    $u3 = User::factory()->create();
    $conv = Conversation::create(['user_one_id' => $u1->id, 'user_two_id' => $u2->id]);

    actingAs($u3)
        ->get(route('messages.show', $conv))
        ->assertStatus(403);
});
