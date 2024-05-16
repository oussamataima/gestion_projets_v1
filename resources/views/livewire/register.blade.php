<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;

new #[Layout('components.layouts.empty')] #[Title('Login')] class
    // <-- The same `empty` layout
    extends Component {
    #[Rule('required')]
    public string $username = '';

    #[Rule('email|unique:users')]
    public string $email = '';

    #[Rule('required|confirmed')]
    public string $password = '';

    #[Rule('required')]
    public string $password_confirmation = '';

    #[Rule('required')]
    public string $role = 'admin';

    public function mount()
    {
        // It is logged in
        if (auth()->user()) {
            return redirect('/');
        }
    }

    public function register()
    {
        $data = $this->validate();
        $data['avatar'] = '/empty-user.jpg';
        $data['role'] = 'admin';
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        // dd($data);

        auth()->login($user);

        request()->session()->regenerate();

        return redirect('/');
    }
};

?>

<div
    style="max-width: 1000px;
             margin-inline: auto;
             margin-top: 4rem;
             padding: 0 2rem
            ">
    {{-- <div class="mb-10">Cool image here</div> --}}

    <x-form wire:submit="register">
        <x-input label="Username" wire:model="username" icon="o-user" inline />
        <x-input label="E-mail" wire:model="email" icon="o-envelope" inline />
        <x-input label="Password" wire:model="password" type="password" icon="o-key" inline />
        <x-input label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key" inline />

        <x-slot:actions>
            <x-button label="Already registered?" class="btn-ghost" link="/login" />
            <x-button label="Register" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="register" />
        </x-slot:actions>
    </x-form>
    {{-- <h1 class="text-center">hello</h1> --}}
</div>
