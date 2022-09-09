<?php

namespace App\Console\Commands\Anita;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class Admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anita:admin {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the administrator role for the user';

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
        $user = User::find($this->argument('user_id'));

        if (!$user) {
            $this->error(sprintf('User ID %s not found.', $this->argument('user_id')));
            return 0;
        }

        $user->role = User::ROLE_ADMIN;
        $user->save();

        if ($user->role === User::ROLE_ADMIN) {
            $this->info(sprintf(
                'The user "%s" has the administrator role installed.',
                $user->email
            ));

        }

        return 0;
    }
}
