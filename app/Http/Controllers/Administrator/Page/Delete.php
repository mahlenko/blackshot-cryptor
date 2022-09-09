<?php

namespace App\Http\Controllers\Administrator\Page;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Repositories\SettingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Page\Page,uuid'
        ]);

        $page = Page::where(['uuid' => $data['uuid']])->first();
        $page_arr = $page->toArray();

        if (!$page->parent_id) {
            flash(__('messages.fail.delete_homepage'))->warning();
            return back();
        }

        if ($page->delete()) {
            flash(__('messages.success.delete', ['name' => $page_arr['name']]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return redirect()->route('admin.page.home', [
            'uuid' => $page_arr['parent_id'],
        ]);
    }
}
