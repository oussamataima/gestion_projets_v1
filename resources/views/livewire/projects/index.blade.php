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
            // ['key' => 'id', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'name', 'label' => 'Full name', 'class' => 'w-35'],
            // ['key' => 'description', 'label' => 'description', 'class' => 'w-50'],
            ['key' => 'start_date', 'label' => 'start date', 'class' => 'w-28'],
            ['key' => 'due_date', 'label' => 'due    date', 'sortable' => 'w-23'],
            ['key' => 'status', 'label' => 'status', 'sortable' => 'w-10'],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     */
    public function projects(): LengthAwarePaginator
{
    return Project::query()
        ->paginate(10);
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
    <x-header title="projects" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" /> --}}
            <x-button icon="o-user-plus" class="btn-primary" link="{{route('projects.create')}}">
                Add project
            </x-button>
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$projects" :sort-by="$sortBy" with-pagination>
            
            @scope('actions', $project)
            <div class="flex flex-nowrap gap-2">
                <x-button link="{{ route('projects.edit', $project) }}" icon="o-pencil" class="btn-sm btn-ghost" />
                <x-button icon="o-trash" wire:click="delete({{ $project['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500" />
            </div>
            
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
{{-- <h1>list users

</h1> --}}