<?php

use App\Models\User;
use App\Models\Project;
use Livewire\Volt\Component;
use Mary\Traits\Toast;


new class extends Component {
    use Toast;

    public Project $project;

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

    


    
}; ?>

<div>
   {{-- {{$project->employers}} --}}
   <x-header class="!mb-0" :title="$project->name"  separator />
    <div>
        <h2 class="text-xl font-bold">Description:</h2>
        <p>{{$project->description}}</p>
    </div>
    <div>
        <h3 class="text-xl font-bold mb-4">Membres</h3>
        <div class="max-w-[800px] mx-auto">
            {{-- <x-choices
            label="Searchable + Multiple"
            wire:model="user_searchable_ids"
            :options="$usersSearchable"
            search-function="search"
            no-result-text="Ops! Nothing here ..."
            searchable /> --}}
            <form wire:submit="add_member">
                <x-choices
                    wire:model="selectedUserIds"
                    :options="$usersSearchable"
                    search-function="search"
                    no-result-text="Ops! Nothing here ..."
                    searchable
                >
            </form>
                {{-- Item slot--}}
                @scope('item', $user)
                    <x-list-item :item="$user" sub-value="bio">
                        <x-slot:avatar>
                            <img class="rounded-full w-12" src="{{$user->avatar ?? "/empty-user.jpg"}}" alt="user avatar">
                        </x-slot:avatar>
                        {{-- <x-slot:actions>
                            <x-badge :value="$user->full_name" />
                        </x-slot:actions> --}}
                        <x-slot:value>
                        {{$user->full_name}}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{$user->profession->name}}
                    </x-slot:sub-value>
                    </x-list-item>
                @endscope
             
                {{-- Selection slot--}}
                @scope('selection', $user)
                    {{ $user->full_name }}
                @endscope
                <x-slot:append>
                    <x-button type="submit" label="Add member" icon="o-plus" class="rounded-l-none btn-primary" />
                </x-slot:append>
            </x-choices>
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
</div>