<?php

namespace App\View\Components;

use App\Http\Controllers\Administrator\Widget\Services\HtmlBlockService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Widget extends Component
{
    /**
     * UUID созданного виджета
     * @var null
     */
    public $uuid = null;

    /**
     * @var null
     */
    public $itemclass = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $uuid, string $itemclass = null)
    {
        $this->uuid = $uuid;
        $this->itemclass = $itemclass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     * @throws \Exception
     */
    public function render()
    {
        /* @var \App\Models\Widget\Widget $widget */
        $widget = \App\Models\Widget\Widget::find($this->uuid);
        if (!$widget) return '';

        if ($widget->type == '\\'.HtmlBlockService::class) {
            return $widget->result();
        }

        return view($widget->templatePath(), [
            'items' => $widget->result(),
            'widget' => $widget,
            'component' => $this,
        ]);
    }
}
