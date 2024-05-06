<?php

function getTimeLeftObject($targetDate) {
    // Convert target date/time string to a timestamp
    $targetTimestamp = strtotime($targetDate);
  
    // Get the current timestamp
    $currentTime = time();
  
    // Calculate the time difference in seconds
    $timeDiff = $targetTimestamp - $currentTime;
  
    // Check if the target date/time has already passed
    if ($timeDiff < 0) {
      return (object) ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
    }
  
    // Calculate remaining days using integer division
    $days = intdiv($timeDiff, 60 * 60 * 24);
  
    // Calculate remaining hours using modulo operator
    $hours = intdiv($timeDiff % (60 * 60 * 24), 60 * 60);
  
    // Calculate remaining minutes using modulo operator again
    $minutes = intdiv($timeDiff % (60 * 60), 60);
  
    // Calculate remaining seconds using another modulo operation
    $seconds = $timeDiff % 60;
    return (object) [
        'days' => $days,
        'hours' => $hours,
        'minutes' => $minutes,
        'seconds' => $seconds,
    ];
  }
