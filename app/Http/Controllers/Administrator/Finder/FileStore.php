<?php

namespace App\Http\Controllers\Administrator\Finder;

use App\Http\Controllers\Controller;
use App\Models\Finder\File;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FileStore extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Finder\File,uuid',
            'locale' => ['required', Rule::in(config('translatable.locales'))],
            'name' => 'required',
            'alt' => 'nullable',
            'title' => 'nullable',
            'description' => 'nullable',
            'class' => 'nullable',
        ]);

        /* сохраняем страницу значения */
        $store = (new FileRepository)->store($data['locale'], $data);

        if ($store) {
            $file = File::where(['uuid' => $data['uuid']])->first();

            if ($request->ajax()) {
                return [
                    'ok' => true,
                    'message' => __('messages.success.save', [
                        'name' => $file->name
                    ])
                ];
            } else {
                flash(__('messages.success.save', ['name' => $file->name]))->success();
                return redirect()->route('admin.finder.file.edit', ['locale' => app()->getLocale(), 'uuid' => $data['uuid']]);
            }
        } else {
            if ($request->ajax()) {
                return [
                    'ok' => false,
                    'message' => __('messages.fail.save')
                ];
            } else {
                flash(__('messages.fail.save'))->error();
                return back()->withInput($data);
            }
        }
    }
}
