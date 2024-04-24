<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
 
new
#[Layout('components.layouts.empty')]       // <-- Here is the `empty` layout
#[Title('Login')]
class extends Component {
 
    #[Rule('required')]
    public string $username = '';
 
    #[Rule('required')]
    public string $password = '';
 
    public function mount()
    {
        // It is logged in
        if (auth()->user()) {
            return redirect('/');
        }
    }
 
    public function login()
    {
        $credentials = $this->validate();
 
        if (auth()->attempt($credentials)) {
            request()->session()->regenerate();
 
            return redirect()->intended('/');
        }
 
        $this->addError('user', 'The provided credentials do not match our records.');
    }
}
?>

<div class= "flex h-screen">
 
    <x-form class="md:w-[420px] m-auto" wire:submit="login">
        <x-errors title="Oops!" description="Please, fix them." icon="o-face-frown" />

        <x-input label="Username" wire:model="username" icon="o-envelope" inline />
        <x-input label="Password" wire:model="password" type="password" icon="o-key" inline />
 
        <x-slot:actions>
            <x-button label="Create an account" class="btn-ghost" link="/register" />
            <x-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />
        </x-slot:actions>
    </x-form>
</div>