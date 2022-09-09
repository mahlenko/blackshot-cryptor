<?php

namespace App\Console\Commands\Anita;

use App\Models\User;
use DomainException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDOException;

class Install extends Command
{
    /**
     * Download url config example
     */
    const EXAMPLE_CONFIG_URL = 'https://bitbucket.org/mahlenko/anita-shop-cms/raw/2798b1ca994d28493453218a3f9505867cbf0369/.env.example';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anita:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh install Anita CMS';

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
        try {
            $this->createConfig();
            Artisan::call('config:clear');
            Artisan::call('storage:link');
        } catch (DomainException $exception) {
            $this->error($exception->getMessage());
            return false;
        }

        try {
            DB::getPdo();

            Artisan::call('anita:locales', [], $this->getOutput());
            Artisan::call('migrate:fresh');
            Artisan::call('anita:registration', [], $this->getOutput());
            Artisan::call('anita:admin', ['user_id' => 1], $this->getOutput());
            Artisan::call('db:seed', ['--class' => 'InstallerSeeder']);

            $this->finishMessage();

        } catch (PDOException $exception) {
            if ($exception->getCode() == 1045) {
                $this->setupDatabase();

                $this->warn('To continue, run the installer again.');
                $this->warn('Before continuing the installation, make sure that the "'. env('DB_DATABASE') .'" database has been created');
            } else {
                $this->error($exception->getMessage());
            }
        }

        return 0;
    }

    /**
     *
     */
    private function createConfig(): void
    {
        $config = base_path('.env');
        $config_example = base_path('.env.example');

        if (!file_exists($config)) {
            if (!file_exists($config_example)) {
                file_put_contents(
                    $config_example,
                    file_get_contents(self::EXAMPLE_CONFIG_URL)
                );

                if (!file_exists($config_example)) {
                    throw new DomainException('Error config download. Get file ' . self::EXAMPLE_CONFIG_URL);
                }
            }

            copy($config_example, $config);
            if (!file_exists($config)) {
                throw new DomainException('Error created config file '. $config);
            }

            if (!env('APP_KEY')) Artisan::call('key:generate --ansi');
        }

    }

    /**
     *
     */
    private function setupDatabase()
    {
        $default = config('database.default');
        $databases = array_keys(config('database.connections'));

        self::envUpdate('DB_CONNECTION', $this->choice('Connection', $databases, $default));

        self::envUpdate('DB_HOST', $this->ask('DB Host', env('DB_HOST', 'mysql')));
        self::envUpdate('DB_PORT', $this->ask('Port', env('DB_PORT', 3306)));
        self::envUpdate('DB_USERNAME', $this->ask('DB username', env('DB_USERNAME', 'root')));
        self::envUpdate('DB_PASSWORD', $this->askWithCompletion('Password', null));
        self::envUpdate('DB_DATABASE', $this->askWithValidation('Database name', null, ['required']));
    }

    /**
     *
     */
    private function finishMessage()
    {
        $user = User::find(1);

        $messages_block = [
            [
                env('APP_NAME') . ' (Laravel '. app()->version().')'
            ],
            [
                'Домен: '. URL::route('home'),
                'Авторизация: '. URL::route('login')
            ],
            [
                'Email: '. $user->email,
                'Пароль: *****'
            ]
        ];

        $padding = 2;
        $width = 0;
        foreach (Arr::flatten($messages_block) as $row) {
            $len = Str::length($row);
            if ($len > $width) $width = $len;
        }

        $width += ($padding * 2) + 2;
        $separator = Str::repeat('*', $width);
        $start = '*' . Str::repeat(' ', $padding);
        $this->info($separator);

        foreach ($messages_block as $block) {
            foreach ($block as $line) {
                $padding_end = $width - Str::length($line) - $padding - 2;
                $end = Str::repeat(' ', $padding_end) . '*';
                $this->info($start . $line . $end);
            }
            $this->info($separator);
        }
    }

    /**
     * @param string $key
     * @param string|null $value
     * @return false
     */
    private static function envUpdate(string $key, $value = null): bool
    {
        $config = base_path('.env');
        if (!file_exists($config)) return false;

        /* @var string $read */
        $read = Str::replace("\r", "\n", file_get_contents($config));
        $content_arr = explode("\n", $read);

        foreach ($content_arr as $line => $row) {
            if (empty($row)) continue;

            if (Str::startsWith(trim($row), '#')) {
                unset($content_arr[$line]);
                continue;
            }

            list($_key, $_value) = explode('=', $row);
            if (Str::startsWith(trim($_value), '#')) $_value = '';

            $key = Str::upper($key);
            $_key = Str::upper(trim($_key));
            $_value = trim($_value);

            if ($_key === $key) {
                $value = trim($value);
                if (strpos($value, ' ')) $value = '"' . $value .'"';

                $_value = $value;
            }

            $content_arr[$line] = $_key .'='. $_value;
        }

        $header_file = '# '. implode("\n# ", [
            '-------------------------------------------------',
            'Generated by ' . env('APP_NAME'),
            'Generate date: ' . date('d.m.Y H:i'),
            '-------------------------------------------------'
        ]);

        return file_put_contents($config, $header_file ."\n". implode("\n", $content_arr));
    }
}
