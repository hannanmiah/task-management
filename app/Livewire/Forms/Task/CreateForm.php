<?php

namespace App\Livewire\Forms\Task;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateForm extends Form
{
    public string $title;
    public string $description;
    public $due_date;
    public string $status;
}