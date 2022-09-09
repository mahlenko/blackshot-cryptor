<?php

namespace App\Http\Controllers\Administrator\Setting\Website;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Repositories\SettingRepository;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index(string $locale)
    {
        return view('administrator.setting.website.index', [
            'locale' => $locale,
            'settings' => SettingRepository::all('website', $locale),
            'pages' => (new Nested(Page::class))->optGroup(app()->getLocale()),
        ]);
    }
}
