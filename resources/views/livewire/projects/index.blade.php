<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator; 
use Livewire\Attributes\Url;
use App\Models\Project;



new class extends Component {
    use Toast;
    use WithPagination; 

    #[Url]
    public string $search = '';

    #[Url]
    public string $state = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }


    public function delete($id): void
    {
        if (!auth()->user()->isAdmin()) {
            return;
        }
    
        $project = Project::find($id);
    
        if ($project) {
            DB::transaction(function () use ($project) {
                // Delete associated tasks
                $project->tasks()->delete();
    
                // Then delete the project
                $project->delete();
            });
    
            $this->warning("Deleted project #$id", '', position: 'toast-bottom');
        } else {
            $this->warning("Project #$id not found", '', position: 'toast-bottom');
        }
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'name', 'label' => 'Project name', 'class' => 'w-42'],
            ['key' => 'manager.full_name', 'label' => 'Manager', 'class' => 'w-36'],
            ['key' => 'start_date', 'label' => 'Start date', 'class' => 'w-32'],
            ['key' => 'due_date', 'label' => 'Due date', 'class' => 'w-32'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-20'],
        ];
    }

 
    public function projects(): LengthAwarePaginator
    {
        $user = auth()->user();
        $query ;
        if ($user->role === 'employer') {
            $query = $user->projects_employer()
                            ->with(['manager'])
                            ->where('name', 'like', "%$this->search%");
        } elseif ($user->role === 'manager') {
            $query = Project::query()
                            ->with(['manager'])
                            ->where('name', 'like', "%$this->search%")
                            ->where('assigned_to', $user->id);
        } else {
            $query = Project::query()
                            ->with(['manager'])
                            ->where('name', 'like', "%$this->search%");
        }

        if ($this->state && $this->state !=="all") {
            $query->where('status', $this->state);
        }

        return $query->paginate(10);
    }

    public function with(): array
    {
        return [
            'projects' => $this->projects(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Projects" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
            @if(auth()->user()->isAdmin())
            <x-button icon="o-plus" class="btn-primary" link="{{route('projects.create')}}">
                Add project
            </x-button>
            @endif
        </x-slot:actions>
    </x-header>


    <!-- TABLE  -->
    <x-card>
        <x-table class="text-center" :headers="$headers" :rows="$projects"  with-pagination link="/projects/{id}">
            @if(auth()->user()->isAdmin())
                @scope('actions', $project)
                    <div class="flex flex-nowrap gap-2">
                        <x-button link="{{ route('projects.edit', $project) }}" icon="o-pencil" class="btn-sm btn-ghost" />
                        <x-button icon="o-trash" wire:click="delete({{ $project['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500" />
                    </div>
                @endscope
            @endif

            @scope('cell_status', $project)
                @switch($project->status)
                    @case("pending")
                        <x-badge value="{{$project->status}}" class="capitalize badge badge-outline badge-warning" />               
                    @break
                    @case("in_progress")
                        <x-badge value="{{$project->status}}" class="capitalize badge badge-outline badge-primary" />               
                    @break
                    @case("completed")
                        <x-badge value="{{$project->status}}" class="capitalize badge badge-outline badge-success" />               
                    @break
                
                    @default
                        
                @endswitch
            @endscope
           
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />
        <select wire:model.live="state" class="select select-primary w-full mt-2">
            <option disabled>Status</option>
            <option value="all" >All</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In progress</option>
            <option value="completed">Completed</option>
          </select>

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
