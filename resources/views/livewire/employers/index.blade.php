<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator; 
use Livewire\Attributes\Url;


new class extends Component {
    use Toast;
    use WithPagination; 

    #[Url]
    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'full_name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        DB::transaction(function () use ($id) {
            User::find($id)->deleteSkills();
            User::find($id)->delete();
        });
        $this->warning("Deleted user #$id", '', position: 'toast-bottom'); 
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'avatar', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'full_name', 'label' => 'Full name', 'class' => 'w-48'],
            ['key' => 'username', 'label' => 'Username', 'class' => 'w-40'],
            ['key' => 'profession.name', 'label' => 'Profession', 'class' => 'w-28'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     */
    public function users(): LengthAwarePaginator
    {
        // return collect([
        //     ['id' => 1, 'name' => 'Mary', 'email' => 'mary@mary-ui.com', 'age' => 23],
        //     ['id' => 2, 'name' => 'Giovanna', 'email' => 'giovanna@mary-ui.com', 'age' => 7],
        //     ['id' => 3, 'name' => 'Marina', 'email' => 'marina@mary-ui.com', 'age' => 5],
        // ])
        //     ->sortBy([[...array_values($this->sortBy)]])
        //     ->when($this->search, function (Collection $collection) {
        //         return $collection->filter(fn(array $item) => str($item['name'])->contains($this->search, true));
        //     });

        return User::query()
        ->with(['profession'])
        ->where('role','employer') 
        ->where('full_name', 'like', "%$this->search%")
        // ->orderBy(...array_values($this->sortBy))
        ->paginate(10);
    }

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Employers" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" /> --}}
            <x-button icon="o-user-plus" class="btn-primary" link="{{route('employers.create')}}">
                Add employer
            </x-button>
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination>
            @scope('cell_avatar', $user)
                <img class="rounded-full" src="{{$user->avatar ?? "/empty-user.jpg"}}" />
            @endscope
            @scope('actions', $user)
            <div class="flex flex-nowrap gap-2">
                <x-button link="{{ route('employers.edit', $user) }}" icon="o-pencil" class="btn-sm btn-ghost" />
                <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-500" />
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