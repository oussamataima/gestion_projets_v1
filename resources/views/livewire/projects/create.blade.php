<?php

use App\Models\User;
use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;


new class extends Component {
    use Toast;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public string $description= '';

    public int $manager_id ;

    #[Validate('required|date')]
    public string  $start_date ;

    #[Validate('date')]
    public ?string $due_date ;

    public function save(): void
        {

            $data = $this->validate();
            $data['assigned_to'] = $this->manager_id;
            $data['created_by'] = auth()->user()->id;
            $data['start_date'] = $this->start_date;
            $data['due_date'] = $this->due_date;
            $project = Project::create($data);
            if($project) {
                $this->success('Project has been created', redirectTo: route('projects.index'));
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
                        ->get()
        ];
    }
    
}; ?>
<div>
    <x-header title="Create Project" separator />
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
             <x-choices
                label="Assign manager"
                wire:model="manager_id"
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
                <x-button label="Cancel" class="btn-ghost" link="/employer" />
                <x-button label="Create" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="register" />
            </x-slot:actions>
        </x-form>
    </div>

</div>