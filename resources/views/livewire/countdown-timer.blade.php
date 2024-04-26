<div id="countdown" class="grid grid-flow-col gap-5 text-center auto-cols-max"

wire:init="startTimer"

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

<script>
    document.addEventListener('livewire:load', function () {
    @this.startTimer();
    });
    
    Livewire.on('startTimer', date => {
    const countDownDate = new Date(date).getTime();
    
    const myfunc = setInterval(function() {
    var now = new Date().getTime();
    var timeleft = countDownDate - now;
    
    var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
    var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
    
    // Result is output to the specific element
    document.getElementById("days").style.setProperty('--value', days) 
    document.getElementById("hours").style.setProperty('--value', hours) 
    document.getElementById("minutes").style.setProperty('--value', minutes)
    document.getElementById("seconds").style.setProperty('--value', seconds)
    
    }, 1000);
    });
    </script>
    