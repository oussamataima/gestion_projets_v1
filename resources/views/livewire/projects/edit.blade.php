<?php

use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;
use Livewire\WithFileUploads;

new class  extends Component
{
    use Toast;
    use WithFileUploads;

    public Project $project;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public string $description = '';

    #[Rule('required')]
    public string $status = '';

    

    public function mount(Project $project): void
    {
        $this->fill($this->project);
        
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $this->project->update($data);

        $this->success('Project has been updated', redirectTo: route('projects.index'));
    }

    
}
 ?>
<div>
    <x-header title="Edit Project" separator />
    <div class="max-w-[700px] mx-auto">
        <x-form wire:submit="save" >
            <x-input label="Name" wire:model="name"  placeholder="name" clearable />
            <x-input label="Description" wire:model="description"  placeholder="description" clearable />
            <x-input label="Status" wire:model="status" placeholder="status" clearable />
            
            <x-slot:actions name="actions">
                <x-button label="Cancel" class="btn-ghost" link="{{ route('projects.index') }}" />
                <x-button label="Update" type="submit" icon="o-paper-airplane" class="btn-warning" spinner="register" />
            </x-slot:actions>
        </x-form>
    </div>
</div>