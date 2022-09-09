<?php

declare(strict_types=1);

namespace App\Stores;

use App\Models\Navigation\Navigation;
use Illuminate\Support\Str;

class NavigationStore
{
    /**
     * @param array $data
     * @return bool
     */
    public function handle(array $data): bool
    {
        $navigation = Navigation::where('uuid', $data['uuid'])->first();

        if (!$navigation) {
            $navigation = new Navigation();
            $navigation->uuid = $data['uuid'];
        }

        if (empty($data['key'])) $data['key'] = $data['name'];

        /* Заполняем данными из формы */
        $navigation->name = $data['name'];
        $navigation->key = Str::slug($data['key']);
        $navigation->description = $data['description'];
        $navigation->template = $data['template'];
        $navigation->cached = $data['cached'] ?? false;

        /* Проверка что нет дубликата */
        $is_double = Navigation::where(['key' => $navigation->key])
            ->where('uuid', '<>', $navigation->uuid)
            ->count();
        if ($is_double) {
            flash(__('navigation.is_double'))->error();
            return false;
        }

        return $navigation->save();
    }
}
