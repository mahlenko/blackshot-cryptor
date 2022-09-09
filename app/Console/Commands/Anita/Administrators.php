<?php

namespace App\Console\Commands\Anita;

use App\Models\User;
use Illuminate\Console\Command;

class Administrators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anita:administrators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the application\'s administrators';

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
            ->where('role', User::ROLE_ADMIN)
            ->get();

        $this->table($headers, $users);
        return 0;
    }
}
