<?php

namespace App\Http\Controllers\Administrator\Widget;

use App\Http\Controllers\Controller;
use App\Models\Widget\Widget;
use App\Repositories\WidgetRepository;
use Illuminate\Http\Request;

class Edit extends Controller
{
    /**
     * @param string|null $uuid
     */
    public function index(string $uuid = null)
    {
        $widget = Widget::find($uuid);
        if (!empty($uuid) && !$widget) {
            abort(404);
        }

        return view('administrator.widget.edit', [
            'widget' => $widget,
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template'=> 'administrator.widget.tabs.general',
                    'data' => [
                        'types' => (new WidgetRepository())->types(),
                        'templates' => (new WidgetRepository())->templates($widget)
                    ]
                ]
            ]
        ]);
    }

}
