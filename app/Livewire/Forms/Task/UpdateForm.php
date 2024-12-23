<?php

namespace App\Livewire\Forms\Task;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    #[Validate('string|max:255')]
    public string $title;
    #[Validate('string')]
    public string $description;
    #[Validate('nullable|date')]
    public $due_date;
    #[Validate('string|in:pending,in-progress,completed')]
    public string $status;
}
