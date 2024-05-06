<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = bcrypt($this->argument('password')); // Hash the password

        $user = User::create([
            'username' => $username,
            'role' => 'admin',
            'avatar' => 'empty-user.jpg',
            'password' => $password,
        ]);

        $this->info("User '$username' created successfully!");
    }
}
