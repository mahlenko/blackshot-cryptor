<?php

namespace App\Console\Commands\Anita;

use App\Models\User;
use Illuminate\Console\Command;

class Users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anita:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the application\'s users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $headers = ['id', 'name', 'email', 'created_at'];
        $users = User::select($headers)
            ->where('role', User::ROLE_USER)
            ->get();

        $this->table($headers, $users);
        return 0;
    }
}
