<?php

namespace App\Http\Controllers\Administrator\Navigation\Items;

use App\Http\Controllers\Controller;
use App\Models\Navigation\NavigationItem;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /* @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @todo Сделать удаление ссылки + кнопка удаления файла
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Navigation\NavigationItem,uuid'
        ]);

        $item = NavigationItem::where(['uuid' => $data['uuid']])->first();
        $page_arr = $item->toArray();

        if ($item->delete()) {
            flash(__('messages.success.delete', ['name' => $page_arr['name']]))->success();
        } else {
            flash(__('messages.fail.delete'))->danger();
        }

//        return redirect()->back();
        return redirect()->route('admin.navigation.items.home', [
            'uuid' => $page_arr['navigation_uuid'],
            'parent_id' => $page_arr['parent_id']
        ]);
    }
}
