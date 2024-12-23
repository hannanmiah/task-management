<?php

use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->task = Task::factory()->for($this->user)->create();
    Task::factory(5)->create();

    $this->payload = [
        'title' => 'New Task',
        'description' => 'This is a new task.',
        'due_date' => now()->addDays(7)->toDateString(),
        'status' => 'pending',
    ];
});
describe('index', function () {
    it('returns a paginated list of tasks', function () {
        $response = $this->actingAs($this->user)->getJson(route('tasks.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'title',
                    'description',
                    'due_date',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    });

    test('unauthenticated',function (){
        $response = $this->getJson(route('tasks.index'));
        $response->assertStatus(401);
    });
});

describe('show',function (){
    it('returns a task', function () {
        $response = $this->actingAs($this->user)->getJson(route('tasks.show', $this->task));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'description',
                'due_date',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
    });

    test('unauthenticated',function (){
        $response = $this->getJson(route('tasks.show', $this->task));
        $response->assertStatus(401);
    });

    test('unauthorized',function (){
        $anotherUserTask = Task::factory()->create();
        $response = $this->actingAs($this->user)->getJson(route('tasks.show', $anotherUserTask));
        $response->assertStatus(403);
    });
});

describe('store',function (){
    it('creates a new task', function () {
        $response = $this->actingAs($this->user)->postJson(route('tasks.store'), $this->payload);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'description',
                'due_date',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
    });

    test('unauthenticated',function (){
        $response = $this->postJson(route('tasks.store'), $this->payload);
        $response->assertStatus(401);
    });
});

describe('update',function (){
    it('updates a task', function () {
        $response = $this->actingAs($this->user)->putJson(route('tasks.update', $this->task), $this->payload);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'title',
                'description',
                'due_date',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
    });

    test('unauthenticated',function (){
        $response = $this->putJson(route('tasks.update', $this->task), $this->payload);
        $response->assertStatus(401);
    });

    test('unauthorized',function (){
        $anotherUserTask = Task::factory()->create();
        $response = $this->actingAs($this->user)->putJson(route('tasks.update', $anotherUserTask), $this->payload);
        $response->assertStatus(403);
    });
});

describe('destroy',function (){
    it('deletes a task', function () {
        $response = $this->actingAs($this->user)->deleteJson(route('tasks.destroy', $this->task));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks',['id' => $this->task->id]);
    });

    test('unauthenticated',function (){
        $response = $this->deleteJson(route('tasks.destroy', $this->task));
        $response->assertStatus(401);
    });

    test('unauthorized',function (){
        $anotherUserTask = Task::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson(route('tasks.destroy', $anotherUserTask));
        $response->assertStatus(403);
    });
});
