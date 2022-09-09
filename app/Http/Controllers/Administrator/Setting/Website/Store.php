<?php

namespace App\Http\Controllers\Administrator\Setting\Website;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class Store extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function index(Request $request): RedirectResponse
    {
        $locale = $request->validate([
            'locale' => ['required', Rule::in(config('translatable.locales'))],
        ])['locale'];

        $data = $request->validate([
            'name' => 'nullable|max:255',
            'description' => 'nullable|max:255',
        ]);

        $files = $request->validate([
            'logotype' => 'nullable|image',
            'favicon' => 'nullable|image',
        ]);

        /*  */
        foreach ($data as $parameter => $value) {
            try {
                SettingRepository::set($locale, 'website.'.$parameter, $value);
            } catch (\Exception $exception) {
                flash($exception->getMessage())->error();
                return back()->withInput();
            }
        }

        if ($files) {
            foreach ($files as $parameter => $file) {
                try {
                    $setting = SettingRepository::get('website.'.$parameter);
                    if (!$setting) {
                        $setting = SettingRepository::set($locale, 'website.' . $parameter);
                    }

                    /* загрузим новый файл */
                    $folder = Folder::createFolder(Setting::class);
                    $upload = File::upload($file, $folder, $setting->uuid);

                    if ($file) {
                        /* удалим старый файл */
                        if ($setting->translateOrNew($locale)->file) {
                            $setting->translateOrNew($locale)->file->delete();
                        }
                    } else {
                        flash(__('messages.fail.upload', ['name' => $file->getClientOriginalName()]))->error();
                    }

                    /* сохраним UUID файла в настройках */
                    SettingRepository::set($locale, 'website.' . $parameter, $upload->uuid);
                } catch (\Exception $exception) {
                    flash($exception->getMessage())->error();
                    return back()->withInput();
                }
            }
        }

        foreach (config('translatable.locales') as $locale) {
            Cache::forget('setting.website.' . $locale);
        }

        flash(__('messages.success.save', ['name' => __('settings.title') .' -> '. __('settings/website.title')]))->success();
        return back()->withInput();
    }
}
