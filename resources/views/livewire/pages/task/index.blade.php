<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Task;
use App\Livewire\Forms\Task\UpdateForm;
use App\Livewire\Forms\Task\CreateForm;
use Livewire\Attributes\On;

new class extends Component {
    public Collection $tasks;

    public CreateForm $createForm;
    public UpdateForm $updateForm;

    public bool $showModal = false;
    public bool $createModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;

    public ?Task $selectedTask;

    public $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'title', 'label' => 'Title'],
        ['key' => 'description', 'label' => 'Description'],
        ['key' => 'due_date', 'label' => 'Due Date'],
        ['key' => 'status', 'label' => 'Status']
    ];

    public array $statuses = [
        ['label' => 'Completed', 'value' => 'completed'],
        ['label' => 'In-progress', 'value' => 'in-progress'],
        ['label' => 'Pending', 'value' => 'pending'],
    ];

    public function show($id)
    {
        $this->selectedTask = Task::find($id);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->selectedTask = Task::find($id);
        $this->editModal = true;
        $this->updateForm->title = $this->selectedTask->title;
        $this->updateForm->description = $this->selectedTask->description;
        $this->updateForm->due_date = $this->selectedTask->due_date->format('Y-m-d');
        $this->updateForm->status = $this->selectedTask->status;
    }

    public function delete($id)
    {
        $this->selectedTask = Task::find($id);
        $this->deleteModal = true;
    }

    #[On('refresh')]
    public function mount()
    {
        $user = auth()->user();
        $this->tasks = Task::whereBelongsTo($user)->get();
    }

    public function store()
    {
        Task::create([
            'user_id' => auth()->id(),
            'title' => $this->createForm->title,
            'description' => $this->createForm->description,
            'due_date' => $this->createForm->due_date,
            'status' => $this->createForm->status,
        ]);

        $this->createModal = false;
        $this->dispatch('refresh');
    }

    public function update()
    {
        $this->selectedTask?->update([
            'title' => $this->updateForm->title,
            'description' => $this->updateForm->description,
            'due_date' => $this->updateForm->due_date,
            'status' => $this->updateForm->status,
        ]);

        $this->editModal = false;
        $this->dispatch('refresh');
    }

    public function destroy()
    {
        $this->selectedTask?->delete();
        $this->deleteModal = false;
        $this->dispatch('refresh');
    }
}; ?>

<div class="container mx-auto">
    <div class="mt-10 flex flex-col space-y-2 md:space-y-4">
        <section class="flex justify-between">
            <h1 class="text-2xl font-bold">Task Management</h1>
            <x-mary-button wire:click="createModal = true" class="btn-primary">Add Task</x-mary-button>
        </section>
        <x-mary-table :headers="$headers" :rows="$tasks">
            @scope('cell_title', $task)
            {{ str($task->title)->limit(30) }}
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
                <x-mary-button icon="o-eye" wire:click="show({{ $task->id }})" spinner
                               class="btn-xs md:btn-sm btn-success btn-outline"/>
                <x-mary-button icon="o-pencil" wire:click="edit({{ $task->id }})" spinner
                               class="btn-xs md:btn-sm btn-info btn-outline"/>
                <x-mary-button icon="o-trash" wire:click="delete({{ $task->id }})" spinner
                               class="btn-xs md:btn-sm btn-error"/>
            </section>
            @endscope
        </x-mary-table>
    </div>
    <x-mary-modal wire:model="showModal" :title="$selectedTask?->title" separator>
        <div>{{$selectedTask?->description}}</div>

        <x-slot:actions>
            <x-mary-button wire:click="showModal = false" label="Close" class="btn-primary btn-outline"/>
        </x-slot:actions>
    </x-mary-modal>
    <x-mary-modal wire:model="createModal" title="Create A New Task" separator>
        <x-mary-form wire:submit="store">
            <x-mary-input label="Title" wire:model="createForm.title" inline/>
            <x-mary-textarea
                    label="Description"
                    wire:model="createForm.description"
                    placeholder="Task details ..."
                    hint="Max 1000 chars"
                    rows="5"
                    inline/>
            <section class="flex space-x-2 md:space-x-4">
                <x-mary-select
                        label="Status"
                        :options="$statuses"
                        option-value="value"
                        option-label="label"
                        placeholder="Select a status"
                        wire:model="createForm.status"/>
                <x-mary-datetime label="Due Date" wire:model="createForm.due_date" icon="o-calendar"/>
            </section>
            <x-slot:actions>
                <x-mary-button label="Cancel" wire:click="editModal = false"/>
                <x-mary-button label="Save" class="btn-primary" type="submit" spinner="store"/>
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>
    <x-mary-modal wire:model="editModal" :title="$selectedTask?->title" separator>
        <x-mary-form wire:submit="update">
            <x-mary-input label="Title" wire:model="updateForm.title" inline/>
            <x-mary-textarea
                    label="Description"
                    wire:model="updateForm.description"
                    placeholder="Task details ..."
                    hint="Max 1000 chars"
                    rows="5"
                    inline/>
            <section class="flex space-x-2 md:space-x-4">
                <x-mary-select
                        label="Status"
                        :options="$statuses"
                        option-value="value"
                        option-label="label"
                        placeholder="Select a status"
                        wire:model="updateForm.status"/>
                <x-mary-datetime label="Due Date" wire:model="updateForm.due_date" icon="o-calendar"/>
            </section>
            <x-slot:actions>
                <x-mary-button label="Cancel" wire:click="editModal = false"/>
                <x-mary-button label="Save" class="btn-primary" type="submit" spinner="update"/>
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>
    <x-mary-modal wire:model="deleteModal" title="Are you sure?" class="backdrop-blur">
        <div class="mb-5">Delete {{$selectedTask?->title}} ?</div>
        <section class="inline-flex space-x-2 md:space-x-4">
            <x-mary-button label="Delete" class="btn-error btn-outline" @click="$wire.destroy"/>
            <x-mary-button label="Cancel" class="btn-info" wire:click="deleteModal = false"/>
        </section>
    </x-mary-modal>
</div>
