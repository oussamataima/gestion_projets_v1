<?php

use App\Models\User;
use App\Models\Project;
use App\Models\task;
use Livewire\Volt\Component;
use Mary\Traits\Toast;


new class extends Component {
    use Toast;

    public Project $project;
    public Task $task;

    // Selected option
    public ?array $user_searchable_ids;
 
    // Options list
    public $usersSearchable;

    public array $selectedUserIds = [];

    public array $team_user_id = [];
 
    public function mount()
    {
        // Fill options when component first renders
        $this->team_user_id = $this->project->employers->pluck('id')->toArray();
        $this->search();
        // dd($this->selectedUserIds);
    }
 
    // Also called as you type
    public function search(string $value = '')
    {
        $searchResults = User::query()
        ->where('full_name', 'like', "%$value%")
        ->where('role', 'employer')
        ->take(5)
        ->whereNotIn('id', $this->team_user_id)
        ->get();

        $this->usersSearchable = $searchResults;
    }
    public function add_member()
    {
    
        $this->project->employers()->attach($this->selectedUserIds);
        $this->selectedUserIds = [];
        $this->success('New employer has been added');
    }

    public function deleteMember($memberId)
    {
        $this->project->employers()->detach($memberId);
        $this->warning('employer has been removed');



    }

    public function delete( $id): void
{
    if (!auth()->user()->isAdmin()) {
        return;
    }

    $task = Task::find($id);
    
    if ($task) {
        DB::transaction(function () use ($task) {
            // Delete the task
            $task->delete();
        });

        $this->warning("Deleted Task #$id", '', position: 'toast-bottom');
    } else {
        $this->warning("Task #$id not found", '', position: 'toast-bottom');
    }
}

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-40'],
            ['key' => 'due_date', 'label' => 'Due date', 'class' => 'w-32'],
            ['key' => 'estimated_completion_time', 'label' => 'Estimate (hrs)', 'class' => 'w-20'],
            ['key' => 'assigned_to', 'label' => 'Employer', 'class' => 'w-32'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-10'],
        ];
    }
    public function with(): array
    {
        return [
            'headers' => $this->headers()
        ];
    }

    


    
}; ?>

<div>
    {{-- {{$headers}} --}}
   <x-header class="!mb-0" :title="$project->name"  separator />
    <div>
        <h2 class="text-2xl font-bold">Description:</h2>
        <p>{{$project->description}}</p>
    </div>
    @if(auth()->user()->role=='manager')
    <div>
        <h3 class="text-2xl font-bold mb-4">Membres</h3>
        <div class="max-w-xl mx-auto">
            <form wire:submit="add_member">
                <x-choices
                    wire:model="selectedUserIds"
                    :options="$usersSearchable"
                    search-function="search"
                    no-result-text="Ops! Nothing here ..."
                    searchable
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
                    <x-slot:append>
                        <x-button type="submit" label="Add member" icon="o-plus" class="rounded-l-none btn-primary" />
                    </x-slot:append>
                </x-choices>
            </form>
            @forelse ( $project->employers as $employer )
                <x-list-item :item="$employer" >
                    <x-slot:avatar>
                        <img class="rounded-full w-12" src="{{$employer->avatar ?? "/empty-user.jpg"}}" alt="employer avatar">
                    </x-slot:avatar>
                    <x-slot:value>
                        {{$employer->full_name}}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{$employer->profession->name}}
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-button icon="o-trash" class="text-red-500" wire:click="deleteMember({{ $employer['id'] }})" wire:confirm="Are you sure?" spinner />
                    </x-slot:actions>
                </x-list-item>
                
            @empty
                No membre yet
            @endforelse
        </div>
    </div>
    @endif
    <div>
        <div class="flex justify-between my-4">
            <h3 class="text-2xl font-bold ">List tasks</h3>
            @if(auth()->user()->role=='manager')
            <x-button label="Add task" icon="o-plus" class="btn-success" link="{{$project->id}}/tasks/create" />
            @endif
        </div>
        <div>
                <x-card>
                    <x-table class="text-center" :headers="$headers" :rows="$project->tasks" >

                            @scope('actions', $task , $project)
                            @if(auth()->user()->role=='manager')
                                <div class="flex flex-nowrap gap-2">
                                    <x-button link="/projects/{{$project->id}}/tasks/{{$task->id}}/edit" icon="o-pencil" class="btn-sm btn-ghost" />
                                    <x-button icon="o-trash" wire:click="delete( '{{ $task['id'] }}')" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500" />
                                   @endif
                                    @endscope
                            
                            

                            @scope('cell_assigned_to', $task)
                                @php
                                    $name = User::find($task->assigned_to)->full_name;
                                    if (isset($name)) {
                                    echo "<strong>$name</strong>";
                                    } else {
                                    echo 'Not found.'; 
                                    }
                                @endphp
                            @endscope
                            @scope('cell_due_date', $task)
                                @php
                                    $dateTimeObject = new DateTime($task->due_date); 
                                    $formattedDate = $dateTimeObject->format('d F Y  H:i'); 
                                    echo $formattedDate;
                                    // dd($date)
                                @endphp
                            @endscope
            
                        @scope('cell_status', $task)
                            @switch($task->status)
                                @case("pending")
                                    <x-badge value="{{$task->status}}" class="capitalize font-bold badge badge-outline badge-warning" />               
                                @break
                                @case("in_progress")
                                    <x-badge value="{{$task->status}}" class="capitalize font-bold badge badge-outline badge-primary" />               
                                @break
                                @case("completed")
                                    <x-badge value="{{$task->status}}" class="capitalize font-bold badge badge-outline badge-success" />               
                                @break
                            
                                @default
                                    
                            @endswitch
                        @endscope
                       
                    </x-table>
                </x-card>
        </div>
    </div>
</div>