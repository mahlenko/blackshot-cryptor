<?php


namespace App\Repositories;


use App\Http\Controllers\Administrator\Widget\ServiceAbstract;
use App\Models\Widget\Widget;
use Illuminate\Support\Facades\Cache;

class WidgetRepository
{
    /**
     * @param array $data
     * @param array $items
     * @return Widget
     */
    public function store(array $data, array $items): Widget
    {
        $widget = Widget::find($data['uuid']);
        if (!$widget) {
            $widget = new Widget();
            $widget->uuid = $data['uuid'];
        }

        $widget->name = $data['name'];
        $widget->type = $data['type'];
        $widget->template = $data['template'] ?? '';
        $widget->parameters = $items;

        Cache::forget('widget.group.'. $widget->uuid .'.'. app()->getLocale());

        $widget->save();
        return $widget;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function delete(string $uuid): bool
    {
        return Widget::find($uuid)->delete();
    }

    /**
     * @return array
     */
    public function types()
    {
        $path = app_path('Http/Controllers/Administrator/Widget/Services/');
        $types = [];
        foreach (glob($path . '*Service.php') as $service) {
            $name = str_replace('.php', '', basename($service));

            /* @var ServiceAbstract $class */
            $class = '\App\Http\Controllers\Administrator\Widget\Services\\' . $name;
            $types[] = [
                'value' => $class,
                'text' => $class::name()
            ];
        }

        return $types;
    }

    /**
     * @param Widget|null $widget
     * @return array
     */
    public function templates(Widget $widget = null): array
    {
        if (!$widget) return [];

        $path = resource_path('views/web/components/widget/' . $widget->type::templateFolder());

        $templates = [];
        foreach (glob($path . '/*.blade.php') as $template) {
            $templates[] = str_replace('.blade.php', '', basename($template));
        }

        return $templates;
    }
}
