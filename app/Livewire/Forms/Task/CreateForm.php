<?php

namespace App\Livewire\Forms\Task;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    #[Validate('required|string|max:255')]
    public string $title;
    #[Validate('required|string')]
    public string $description;
    #[Validate('nullable|date')]
    public $due_date;
    #[Validate('required|string|in:pending,in-progress,completed')]
    public string $status;
}
