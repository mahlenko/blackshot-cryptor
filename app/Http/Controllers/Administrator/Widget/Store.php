<?php

namespace App\Http\Controllers\Administrator\Widget;

use App\Http\Controllers\Controller;
use App\Models\Widget\Widget;
use App\Repositories\WidgetRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Store extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid',
            'name' => 'required',
            'type' => 'required',
            'template' => 'nullable'
        ]);


        /* Правила обработки полей настроек виджета */
        $fields = [];
        if (Widget::where('uuid', $data['uuid'])->count()) {
            $fields = $request->validate(
                $this->addRulesFields($data['type'])
            );
        }

        $widget = (new WidgetRepository())->store($data, $fields);
        flash(__('messages.success.save', ['name' => $widget->name]))->success();

        return redirect()->route('admin.widget.edit', ['uuid' => $data['uuid']]);
    }

    /**
     * @param string $class
     * @return array
     */
    private function addRulesFields(string $class): array
    {
        /* @var array[] */
        $fields = (new $class)->options();

        $rules = [];
        foreach ($fields as $field) {
            $field_name = preg_replace('/[^a-z0-9_]]/', '', $field['name']);

            if (key_exists('args', $field)) {
                $field_rules = [];

                /* обязательное поле */
                if (key_exists('required', $field['args'])) {
                    $field_rules[] = 'required';
                } else {
                    $field_rules[] = 'nullable';
                }

                /* поле содержит массив */
                if (key_exists('multiple', $field['args'])) {
                    $field_rules[] = 'array';
                }

                if (count($field_rules)) {
                    $rules[$field_name] = implode('|', $field_rules);
                }
            } else {
                $rules[$field_name] = 'nullable';
            }
        }

        return $rules;
    }
}
