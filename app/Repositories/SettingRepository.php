<?php


namespace App\Repositories;


use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Setting;
use App\Models\SettingTranslation;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

/**
 * Настройки приложения
 * @package App\Repositories
 */
class SettingRepository
{
    /**
     * @param string $group
     * @param string|null $locale
     * @return array
     */
    public static function all(string $group, string $locale = null): array
    {
        if (!$locale) $locale = app()->getLocale();

        $settings = Cache::rememberForever('setting.'. $group .'.' . $locale, function() use ($locale) {
            return Setting::where(['group' => 'website'])
                ->with(['file', 'translations'])
                ->get();
        });

        $settings_array = [];
        if ($settings) {
            /* @var Setting $setting */
            foreach ($settings as $setting) {
                $setting->setDefaultLocale($locale);

                $file = !empty($setting->value)
                    ? $setting->translation->file
                    : null;

                if ($file) {
                    $settings_array[$setting->name] = $file;
                } else {
                    $settings_array[$setting->name] = $setting->value;
                }
            }
        }

        return $settings_array;
    }

    /**
     * @param string $parameter
     * @return Setting|null
     * @throws Exception
     */
    public static function get(string $parameter): ?Setting
    {
        if (strpos($parameter, '.') === false) {
            throw new Exception('The parameter must be specified together with the group: [{group}.{parameter}]');
        }

        list($group, $name) = explode('.', $parameter);
        $setting = Setting::where(['group' => $group, 'name' => $name])->first();

        if (!$setting) {
            $setting = new Setting();
            $setting->uuid = Uuid::uuid4();
            $setting->group = $group;
            $setting->name = $name;
        }

        return $setting;
    }

    /**
     * @param string $locale
     * @param string $parameter
     * @param string|null $value
     * @return Setting|null
     * @throws Exception
     */
    public static function set(string $locale, string $parameter, string $value = null): ?Setting
    {
        $setting_parameter = self::get($parameter)->setDefaultLocale($locale);

        if (!$setting_parameter) {
            list($group, $name) = explode('.', $parameter);

            $setting_parameter = new Setting();
            $setting_parameter->uuid = Uuid::uuid4()->toString();
            $setting_parameter->group = $group;
            $setting_parameter->name = $name;
        }

        $setting_parameter->value = $value;
        $setting_parameter->push();

        return $setting_parameter;
    }

    /**
     * @param string $locale
     * @param string $parameter
     * @return bool
     * @throws Exception
     */
    public static function deleteFile(string $locale, string $parameter): bool
    {
        $setting = self::get($parameter)->translateOrDefault($locale);

        if ($setting->file) {
            $setting->file->delete();
            self::set($locale, $parameter, null);

            return true;
        }

        return false;
    }
}
