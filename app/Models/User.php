<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function scopeEmployers(Builder $query): Builder
    {
        return $query->where('role', 'employer')
                    ->select(['id', 'username', 'email', 'full_name', 'profession']); 
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'profession_id',
        'role',
        'password',
        'avatar',
        'skills',
    ];

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function deleteSkills()
    {
        $this->skills()->detach(); // Detach all related skills
    }


    
    // Get the projects that the admin has created.
    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get the projects that the manager is assigned to .
     * 
     */
    public function managedProjects()
    {
        return $this->belongsToMany(Project::class, 'assigned_to');
    }

    public function projects_employer()
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }


    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function role()
    {
        return $this->role;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
