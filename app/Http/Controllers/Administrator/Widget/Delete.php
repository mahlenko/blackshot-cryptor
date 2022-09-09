<?php

namespace App\Http\Controllers\Administrator\Widget;

use App\Http\Controllers\Controller;
use App\Models\Widget\Widget;
use App\Repositories\WidgetRepository;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * Удаление виджета
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Widget\Widget,uuid'
        ]);

        $widget = Widget::find($data['uuid']);

        if ((new WidgetRepository())->delete($data['uuid'])) {
            flash(__('messages.success.delete', ['name' => $widget->name]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return redirect()->route('admin.widget.home');
    }
}
