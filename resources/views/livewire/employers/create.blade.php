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


    #[Validate('image|max:1024')]
    public $avatar;

    #[Rule('required')]
    public string $username = '';

    #[Rule('required')]
    public string $full_name= '';
 
    #[Rule('required|email|unique:users')]
    public string $email = '';

    #[Validate('required|int')]
    public int $profession_id;

    #[Validate('required')]
    public array $skills;
    
    #[Rule('required|confirmed')]
    public string $password = '';

    
    #[Rule('required')]
    public string $password_confirmation = '';
    // public string $role = '';


    
    
        // $data = $this->validate(); 
        // $data['role'] = 'employer';
        // $data['password'] = Hash::make($data['password']);
        // dd($data);
 
        // $user = User::create($data);
        // // dd($data);
        // $this->success('Employer created.', redirectTo: "/employer");
        
        public function save(): void
        {

            $data = $this->validate(); 
            $data['role'] = 'employer';
            $data['profession_id'] = $this->profession_id;
            $user = User::create($data);
            $user->skills()->sync($this->skills);
            // dd($user);
            if($this->avatar) {
                $url = $this->avatar->store('users', 'public');
                $user->update(['avatar' => url("/storage/$url")]);
            }

            $this->success('User has been created', redirectTo: route('employers.index'));
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
    <x-header title="Create Employer" separator />
    <div class="max-w-[700px] mx-auto">
        <x-form wire:submit="save" >
            <x-file wire:model="avatar" accept="image/png, image/jpeg" crop-after-change >
                <img src="/empty-user.jpg" class="h-40 rounded-lg" />
            </x-file>
            
            <x-input label="Name" wire:model="full_name" placeholder="Joe Doe" clearable />
            <x-input label="User Name" wire:model="username" placeholder="username" clearable />
            <x-input label="Email" wire:model="email" placeholder="user@email.com" clearable />
            <x-select label="Profession" wire:model="profession_id" :options="$professions" placeholder="---" />
            <x-choices-offline label="Skills" wire:model="skills" :options="$allSkills" multiple searchable />
            <x-input label="Password" wire:model="password" icon="o-key" type="password" />
            <x-input label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key" />
            <x-slot:actions>
                <x-button label="Cancel" class="btn-ghost" link="/employer" />
                <x-button label="Create" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="register" />
            </x-slot:actions>
        </x-form>
    </div>

</div>