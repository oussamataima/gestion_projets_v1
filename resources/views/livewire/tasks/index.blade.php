<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator; 
use Livewire\Attributes\Url;
use App\Models\Task;



new class extends Component {
    use Toast;
    use WithPagination; 

    #[Url]
    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        if(!auth()->user()->isAdmin()) {
                    return;
                }
        $project = Project::find($id);

        if ($project) {
            DB::transaction(function () use ($project) {
                // Supprimer le projet et ses éventuelles dépendances dans une transaction
                $project->delete();
            });

            $this->warning("Deleted project #$id", '', position: 'toast-bottom'); // Message de confirmation
        } else {
            $this->warning("Project #$id not found", '', position: 'toast-bottom'); // Message si le projet n'est pas trouvé
        }
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-40'],
            ['key' => 'project.name', 'label' => 'Project name', 'class' => 'w-40'],
            ['key' => 'assignedTo.full_name', 'label' => 'Manager', 'class' => 'w-40'],
            ['key' => 'due_date', 'label' => 'Due date', 'class' => 'w-32'],
            ['key' => 'estimated_completion_time', 'label' => 'Estimate (hrs)', 'class' => 'w-20'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-10'],
        ];
    }

 
    public function tasks(): LengthAwarePaginator
    {
        $user = auth()->user();

        return $user->assignedTasks()
                    ->with(['project'])
                    ->with(['assignedTo'])
                    ->where('title', 'like', "%$this->search%")
                    ->paginate(10);
    }

    public function with(): array
    {
        return [
            'tasks' => $this->tasks(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="My tasks" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" /> --}}
        
        </x-slot:actions>
    </x-header>


    <!-- TABLE  -->
    <x-card>
        <x-table class="text-center" :headers="$headers" :rows="$tasks"  with-pagination link="/tasks/{id}">

            @scope('cell_status', $task)
                @switch($task->status)
                    @case("pending")
                        <x-badge value="{{$task->status}}" class="capitalize badge badge-outline badge-warning" />               
                    @break
                    @case("in_progress")
                        <x-badge value="{{$task->status}}" class="capitalize badge badge-outline badge-primary" />               
                    @break
                    @case("completed")
                        <x-badge value="{{$task->status}}" class="capitalize badge badge-outline badge-success" />               
                    @break
                
                    @default
                        
                @endswitch
            @endscope
           
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
