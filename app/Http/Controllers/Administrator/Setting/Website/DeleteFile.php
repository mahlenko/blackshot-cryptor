<?php

namespace App\Http\Controllers\Administrator\Setting\Website;

use App\Http\Controllers\Controller;
use App\Repositories\SettingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeleteFile extends Controller
{
    /**
     * @param Request $request
     * @param string $locale
     * @return RedirectResponse
     * @throws \Exception
     */
    public function index(Request $request, string $locale): RedirectResponse
    {
        $data = $request->validate([
            'parameter' => 'required'
        ]);

        if(SettingRepository::deleteFile($locale, $data['parameter']) ) {
            flash(__('messages.success.delete', ['name' => __('file.file')]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return back();
    }
}
