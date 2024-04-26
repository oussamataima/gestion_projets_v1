<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Url;

require app_path('Http/helpers.php');


new class extends Component {
    use Toast;



    public Task $task;

    public $timeLeft;

    public function mount()
    {
        $this->timeLeft = getTimeLeftObject($this->task->due_date);
    }

    public function refreshTimeLeft()
    {
        $this->timeLeft = $this->getTimeLeftObject($this->dueDate);
    }

 

    
}; ?>

<div>
    <div class="flex justify-between items-baseline mb-8">
        <h1 class="text-2xl lg:text-2xl font-bold ">{{$task->title}}</h1>
        <x-badge value="{{$task->status}}"
                @class([
                        'capitalize p-3 badge badge-outline',
                        'badge-warning' => $task->status === 'pending',
                        'badge-primary' => $task->status === 'in_progress',
                        'badge-success' => $task->status === 'completed',
                       ])
        />
    </div>
    <hr class="mb-4" >
    <div>
        <h2 class="text-2xl font-bold">Description:</h2>
        @isset($task->description)
            <p class="my-2 max-w-[900px] mx-auto">{{$task->description}}</p>     
        @endisset
    </div>
    <div>
      @php
        $time = strtotime($task->due_date);
        $timeNow = time();
        $checkTime = $time > $timeNow ? true : false;
        echo $timeNow;
        echo "<br>";
        echo $time;
        echo $checkTime;
      @endphp
    </div>
    
    <div id="countdown" class="grid grid-flow-col gap-5 text-center auto-cols-max"
         data-test-value="{{$task->due_date}}"
    >
        <div class="flex flex-col">
          <span class="countdown font-mono text-5xl">
            <span id="days" style="--value:100;"></span>

          </span>
          days
        </div> 
        <div class="flex flex-col">
          <span class="countdown font-mono text-5xl">
            <span id="hours" style="--value:10;"></span>
          </span>
          hours
        </div> 
        <div class="flex flex-col">
          <span class="countdown font-mono text-5xl">
            <span id="minutes" style="--value:24;"></span>
          </span>
          min
        </div> 
        <div class="flex flex-col">
          <span class="countdown font-mono text-5xl">
            <span id="seconds" style="--value:38;"></span>
          </span>
          sec
        </div>
      </div>
   
    
      {{-- @livewire('countdown-timer', ['dueDate' => $task->due_date]) --}}
      <script>
        // Define the interval variable outside of the function to make it accessible globally
        var countdownInterval;
        
        function startCountdown() {
        const element = document.getElementById("countdown");
        const date = element.dataset.testValue;
        const countDownDate = new Date(date).getTime();
        
        // Clear any existing intervals before setting a new one
        clearInterval(countdownInterval);
        
        countdownInterval = setInterval(function() {
          var now = new Date().getTime();
          var timeleft = countDownDate - now;
        
        // Calculating the days, hours, minutes and seconds left
        var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
        
        document.getElementById("days").style.setProperty('--value', days);
        document.getElementById("hours").style.setProperty('--value', hours);
        document.getElementById("minutes").style.setProperty('--value', minutes);
        document.getElementById("seconds").style.setProperty('--value', seconds);
        // document.getElementById('countdown').style.display = "none"
        if(timeleft >= now) {
          document.getElementById("countdown").style.display = "none"
        }
        
        }, 1000);
        }
        
        // Listen for the page unload event
        window.onbeforeunload = function() {
        // Clear the countdown interval
        clearInterval(countdownInterval);
        };
        
        // Initialize the countdown timer
        startCountdown();
        </script>
        
</div>




