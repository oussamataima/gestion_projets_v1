<?php


use App\Models\User;
use App\Models\Profession;
use App\Models\Skill;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithFileUploads;


new class extends Component {
    use Toast;
    use WithFileUploads;

    public User $user;


    // #[Validate('image|max:1024')]
    public $avatar;

    #[Rule('required')]
    public string $username = '';

    #[Rule('required')]
    public string $full_name= '';
 
    #[Validate] 
    public string $email = '';

    #[Validate('required|int')]
    public int $profession_id;
    
    #[Validate('required')]
    public array $skills;
    
    public string $password = '';
    
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ];
    }


    public function mount(): void
    {
        $this->fill($this->user);
        $this->skills = $this->user->skills->pluck('id')->all();
    }

        
        public function save(): void
        {

            $data = $this->validate(); 
            $data['role'] = 'employer';
            $data['profession_id'] = $this->profession_id;
            $this->user->update($data);
            $this->user->skills()->sync($this->skills);

            if($this->avatar) {
                $url = $this->avatar->store('users', 'public');
                // $this->user->update(['avatar' => $url]);
                // dd($this->avatar);
                $this->user->update(['avatar' => url("/storage/$url")]);

            }

            $this->success('User has been updated', redirectTo: route('employers.index'));
        }

    
    public function with(): array
    {
        return [
            'professions' => Profession::all(),
            'allSkills' => Skill::all(),
            
        ];
    }
}; ?>
<div>
    <x-header title="Edit Employer" separator />
    <div class="max-w-[700px] mx-auto">
        <x-form wire:submit="save" >
            <x-file wire:model="avatar" accept="image/png, image/jpeg" crop-after-change >
                <img src={{$user->avatar ?? "/empty-user.jpg"}} class="h-40 rounded-lg" />
            </x-file>
            
            <x-input label="Name" wire:model="full_name" placeholder="Joe Doe" clearable />
            <x-input label="User Name" wire:model="username" placeholder="username" clearable />
            <x-input label="Email" wire:model="email" placeholder="user@email.com" clearable />
            <x-select label="Profession" wire:model="profession_id" :options="$professions" placeholder="---" />
            <x-choices-offline label="Skills" wire:model="skills" :options="$allSkills" multiple searchable />
            <x-input label="Password" wire:model="password" icon="o-key" type="password" />
            <x-slot:actions>
                <x-button label="Cancel" class="btn-ghost" link="{{route('employers.index')}}" />
                <x-button label="Update" type="submit" icon="o-paper-airplane" class="btn-warning" spinner="register" />
            </x-slot:actions>
        </x-form>
    </div>

</div>