<?php

namespace App\Livewire;

use Livewire\Component;

class CountdownTimer extends Component
{
    public $dueDate;

    public function mount($dueDate)
    {
    $this->dueDate = $dueDate;
    }

    // Define the startTimer method
    public function startTimer()
    {
    // You can emit an event to the front-end or perform other actions here
    $this->emit('startTimer', $this->dueDate);
    }


    public function render()
    {
        return view('livewire.countdown-timer');
    }
}
