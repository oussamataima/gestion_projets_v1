<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to',
        'estimated_completion_time',
        'start_time',
        'end_time',
        'task_points', // New field for fillable
        'earned_points', // New field for fillable
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault();
    }

    public function getTaskPointsAttribute()
    {
        return $this->attributes['task_points'];
    }

    public function getEarnedPointsAttribute()
    {
        return $this->attributes['earned_points'];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
