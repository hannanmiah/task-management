<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Task;

new class extends Component {
    public Collection $tasks;

    public $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'title', 'label' => 'Title'],
        ['key' => 'description', 'label' => 'Description'],
        ['key' => 'due_date', 'label' => 'Due Date'],
        ['key' => 'status', 'label' => 'Status']
    ];

    public function mount()
    {
        $this->tasks = Task::all();
    }
}; ?>

<div class="container mx-auto">
    <div class="mt-10 flex flex-col space-y-2 md:space-y-4">
        <section class="flex justify-between">
            <h1 class="text-2xl font-bold">Task Management</h1>
            <x-mary-button @click="createTask()" class="btn-primary">Add Task</x-mary-button>
        </section>
        <x-mary-table :headers="$headers" :rows="$tasks">
            @scope('cell_title', $task)
            {{ str($task->description)->limit(30) }}
            @endscope
            @scope('cell_description', $task)
            {{ str($task->description)->limit(50) }}
            @endscope
            @scope('cell_status', $task)
            <x-mary-badge
                    :value="$task->status" @class(['badge-primary' => $task->status === 'completed','badge-warning' => $task->status === 'pending']) />
            @endscope
            @scope('actions', $task)
            <section class="inline-flex space-x-1 md:space-x-2">
                <x-mary-button icon="o-pencil" wire:click="edit({{ $task->id }})" spinner class="btn-xs md:btn-sm btn-info btn-outline"/>
                <x-mary-button icon="o-trash" wire:click="delete({{ $task->id }})" spinner class="btn-xs md:btn-sm btn-error"/>
            </section>
            @endscope
        </x-mary-table>
    </div>
</div>
