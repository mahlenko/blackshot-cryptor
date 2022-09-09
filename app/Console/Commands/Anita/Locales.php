<?php

namespace App\Console\Commands\Anita;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Locales extends Command
{
    /**
     * @var string
     */
    protected $signature = 'anita:locales';

    /**
     * @var string
     */
    protected $description = 'Настроить языковые версии сайта';

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
        Artisan::call('vendor:publish', [
            '--tag' => 'translatable'
        ]);

        $config = config('translatable');

        $locales = $this->ask('Укажите языки сайта (через пробел):', 'ru');

        $config['locales'] = explode(' ', $locales);
        $config['locale'] = $this->askWithCompletion('Язык по-умолчанию:', $config['locales'], $config['locales'][0]);

        file_put_contents(
            config_path('translatable.php'),
            $this->generateCode($config)
        );

        return self::SUCCESS;
    }

    /**
     * @param array $data
     * @return string
     */
    private function generateCode(array $data): string
    {
        $content = Str::replace(
            ['array (', ')', "=> \n"],
            ['[', ']', '=> '],
            var_export($data, true)
        );

        $content = preg_replace('/[ ]+\[/', ' [', $content);

        return "<?php\n\nreturn " . $content .";\n";
    }
}
