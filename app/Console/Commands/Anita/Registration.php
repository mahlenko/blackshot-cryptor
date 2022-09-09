<?php

namespace App\Console\Commands\Anita;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Registration extends Command
{
    protected $name = 'anita:registration';
    protected $description = 'Регистрация пользователя';

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
    public function handle(): int
    {
        $name = $this->askWithValidation('Ваше имя', null, ['required']);
        $email = $this->askWithValidation('Email', null, ['required', 'email', 'unique:App\Models\User,email']);
        $password = $this->askWithValidation('Пароль', null, ['required']);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        return self::SUCCESS;
    }

    /**
     * @param string $question
     * @param $default
     * @param array $rules
     * @return mixed
     */
    private function askWithValidation(string $question, $default = null, array $rules = ['trim'])
    {
        $answer = $this->askWithCompletion($question, $default ? [$default] : $default);
        $validator = Validator::make([$question => $answer], [$question => $rules]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $fail) {
                $this->error($fail);
            }

            return $this->askWithValidation($question, $default, $rules);
        }

        return $answer;
    }
}
