<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'due_date',
        'status',
        'created_by',
        'assigned_to',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function employers()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    

    
}
