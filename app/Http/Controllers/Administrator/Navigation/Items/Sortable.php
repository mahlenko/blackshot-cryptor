<?php

namespace App\Http\Controllers\Administrator\Navigation\Items;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Navigation\Navigation;
use App\Models\Navigation\NavigationItem;
use Illuminate\Http\Request;

/**
 * Сортировка списка ссылок
 * @package App\Http\Controllers\Administrator\Navigation\Items
 */
class Sortable extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'amount' => 'required|numeric'
        ]);

        $object = NavigationItem::find($data['uuid']);

        $nested = new Nested(NavigationItem::class);
        $result_ok = $nested->sortable($data['uuid'], $data['amount']);

        if ($result_ok) {
            Navigation::forgetCache($object->navigation->key);
        }

        return [
            'ok' => $result_ok,
            'message' => $result_ok
                ? __('sortable.message.success', ['name' => $object->name])
                : __('sortable.message.fail', ['name' => $object->name])
        ];
    }
}
