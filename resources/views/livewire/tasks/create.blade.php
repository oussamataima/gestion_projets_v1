<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;


new class extends Component {
    use Toast;

    public Project $project;

    #[Rule('required')]
    public string $title = '';

    
    public string $description= '';

    #[Rule('required')]
    public int $assigned_to ;

    #[Rule('required|gt:0')]
    public int $task_points = 1;

    #[Rule('required||int|gt:0|lt:1000')]
    public int $estimated_completion_time = 1 ;

    #[Validate('required|date')]
    public string $due_date ;

    public function save(): void
        {

            $data = $this->validate();
            $data['description'] = $this->description;
            $data['project_id'] = $this->project->id;
            $data['assigned_to'] = $this->assigned_to;
            $data['due_date'] = $this->due_date;

            // dd($data);
            $task = Task::create($data);
            if($task) {
                $this->success('Project has been created', redirectTo: route('projects.show',$this->project));
            } else {
                $this->success('something went wrong');       
            }
        }
            
        public function with(): array
    {
        return [
            'users' =>  User::query()
                        ->select(['id','full_name', 'avatar' ,'role' , 'username'])
                        ->where('role','manager') 
                        ->get(),
        ];
    }
    
}; ?>
{{-- 
'project_id',
'title',
'description',
'status',
'due_date',
'assigned_to',
'estimated_completion_time',
'start_time',
'end_time',
'task_points', 
'earned_points', --}}
<div>
    <x-header title="Create Task" separator />
    <div class="max-w-[700px] mx-auto">
        <x-form wire:submit="save" >
            <x-input label="Title" wire:model="title"  clearable />
            <x-textarea
                label="Description"
                wire:model="description"
                placeholder="description ..."
                hint="Max 1000 chars"
                rows="5"
             />
                <x-choices
                    label="Assing employer"
                    wire:model="assigned_to"
                    :options="$project->employers"
                    {{-- option_value= "user_id" --}}
                    value="user_id"
                    no-result-text="Ops! Nothing here ..."
                    single
                >
                {{-- Item slot--}}
                @scope('item', $user)
                    <x-list-item :item="$user" sub-value="bio">
                        <x-slot:avatar>
                            <img class="rounded-full w-12" src="{{$user->avatar ?? "/empty-user.jpg"}}" alt="user avatar">
                        </x-slot:avatar>

                        <x-slot:value>
                        {{$user->full_name}}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{$user->profession->name}}
                    </x-slot:sub-value>
                    </x-list-item>
                @endscope
                @scope('selection', $user)
                    {{ $user->full_name }}
                @endscope
                </x-choices>
                <x-datetime label="Due date" wire:model="due_date" icon="o-calendar" type="datetime-local" />
                <x-input label="Estimated completion time (hrs)" wire:model="estimated_completion_time"  clearable />
                <x-range 
                    wire:model.live="task_points"
                    label="Task points"
                    min=1
                    max=10
                     />
                <x-badge :value="$task_points" class="badge-neutral" />
            <x-slot:actions>
                <x-button label="Cancel" class="btn-ghost" link="/projects" />
                <x-button label="Create" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </div>

</div>