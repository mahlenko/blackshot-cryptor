<?php


namespace App\Http\Controllers\Administrator\Widget\Services;

use Exception;
use Illuminate\Support\Facades\Blade;

class HtmlBlockService extends \App\Http\Controllers\Administrator\Widget\ServiceAbstract
{

    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return __('widgets/html.name');
    }

    /**
     * @inheritDoc
     */
    public static function templateFolder(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        return [
            [
                'type' => 'code',
                'mode' => 'php_laravel_blade',
                'name' => 'code',
                'label' => __('widgets/html.columns.code'),
                'args' => [
                    'code' => 'nullable'
                ]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function result(array $parameters, string $widget_uuid = null)
    {
        if (!empty($parameters['code'])) {
            return self::render($parameters['code']);
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public static function clearCacheByObjectUuid(string $object_uuid)
    {
        // TODO: Implement clearCacheByObjectUuid() method.
    }

    /**
     * @param string $string
     * @param array $data
     * @return false|string
     * @throws Exception
     */
    private static function render(string $string, array $data = [])
    {
        $__env = app(\Illuminate\View\Factory::class);
        $string = Blade::compileString($string);

        $obLevel = ob_get_level();
        ob_start();
        extract($data, EXTR_SKIP);

        try {
            eval('?' . '>' . $string);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new Exception($e);
        }

        return ob_get_clean();
    }
}
