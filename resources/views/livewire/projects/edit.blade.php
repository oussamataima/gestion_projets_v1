<?php

use App\Models\User;
use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;


new class extends Component {
    use Toast;

    public Project $project;


    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public string $description= '';

    public ?int $assigned_to ;

    public string $status ;

    #[Validate('required|date')]
    public string  $start_date ;

    #[Validate('date')]
    public ?string $due_date ;

    public function mount(): void
    {
        $this->fill($this->project);
        
    }

    public function save(): void
        {
            if(!auth()->user()->isAdmin()) {
                return;
            }
            
            $data = $this->validate();
            $data['assigned_to'] = $this->assigned_to;
            $data['status'] = $this->status;
            $data['start_date'] = $this->start_date;
            $data['due_date'] = $this->due_date;
            $project = $this->project->update($data);
            if($project) {
                $this->warning('Project has been updated', redirectTo: route('projects.index'));
            } else {
                $this->error('something went wrong');       
            }
        }
            
        public function with(): array
    {
        return [
            'users' =>  User::query()
                        ->select(['id','full_name', 'avatar' ,'role' , 'username'])
                        ->where('role','manager') 
                        ->get()
        ];
    }
    
}; ?>
@php
    $status = [
        ['name' => 'pending' ],
        ['name' => 'in_progress' ],
        ['name' => 'completed' ],
    ]
@endphp
<div>
    <x-header title="Edit Project" separator />
    <div class="max-w-[700px] mx-auto">
        <x-form wire:submit="save" >
            <x-input label="Project Name" wire:model="name"  clearable />
            <x-textarea
                label="Description"
                wire:model="description"
                placeholder="description ..."
                hint="Max 1000 chars"
                rows="5"
             />
             <x-select
                label="Status"
                :options="$status"
                option-value="name"
                placeholder-value="pending" 
                wire:model="status" />
             <x-choices
                label="Assign manager"
                wire:model="assigned_to"
                :options="$users"
                
                option-label="full_name"
                option-sub-label="role"
                option-avatar="avatar"
                icon="o-user"
                height="max-h-96" {{-- Default is `max-h-64`  --}}
                single />
             <x-datepicker label="Start Date:" wire:model="start_date" icon="o-calendar" />
             <x-datepicker label="Due Date:" wire:model="due_date" icon="o-calendar" />
            <x-slot:actions>
                <x-button label="Cancel" class="btn-ghost" link="/projects" />
                <x-button label="Update" type="submit" icon="o-paper-airplane" class="btn-warning" spinner="register" />
            </x-slot:actions>
        </x-form>
    </div>

</div>