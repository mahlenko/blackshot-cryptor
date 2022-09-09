<?php

namespace App\Http\Controllers\Administrator\Navigation;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationRequest;
use App\Models\Navigation\Navigation;
use App\Stores\NavigationStore;
use Illuminate\Http\Request;

/**
 * Сохранение навигационного меню
 * @package App\Http\Controllers\Administrator\Navigation
 */
class Store extends Controller
{
    public function index(NavigationRequest $request)
    {
        $data = $request->validated();

        if ((new NavigationStore())->handle($data)) {
            $navigation = Navigation::where('uuid', $data['uuid'])->first();
            flash(__('messages.success.save', ['name' => $navigation->name]))->success();
            return redirect()->route('admin.navigation.items.home', ['uuid' => $data['uuid']]);
        } else {
            flash(__('messages.fail.save'))->error();
        }

        return redirect()->back()->withInput($data);
    }
}
