<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Url;

require app_path('Http/helpers.php');

new
#[Layout('components.layouts.app')]

new class extends Component {
    use Toast;



    public Task $task;

    public $timeLeft;

    public function mount()
    {
        $this->timeLeft = getTimeLeftObject($this->task->due_date);
    }

    public function refreshTimeLeft()
    {
        $this->timeLeft = $this->getTimeLeftObject($this->dueDate);
    }

 


    
}; ?>

<div>
    <div class="flex justify-between items-baseline mb-8">
        <h1 class="text-2xl lg:text-2xl font-bold ">{{$task->title}}</h1>
        <x-badge value="{{$task->status}}"
                @class([
                        'capitalize p-3 badge badge-outline',
                        'badge-warning' => $task->status === 'pending',
                        'badge-primary' => $task->status === 'in_progress',
                        'badge-success' => $task->status === 'completed',
                       ])
        />
    </div>
    <hr class="mb-4" >
    <div>
        <h2 class="text-2xl font-bold">Description:</h2>
        @isset($task->description)
            <p class="my-2 max-w-[900px] mx-auto">{{$task->description}}</p>     
        @endisset
    </div>
    <button wire:click="$alert('Post saved!')" >button 1</button>
</div>





</div>