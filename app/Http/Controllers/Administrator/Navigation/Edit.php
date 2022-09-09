<?php

namespace App\Http\Controllers\Administrator\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Navigation;
use App\Repositories\NavigationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

/**
 * Создание и редактирование меню
 * @package App\Http\Controllers\Administrator\Navigation
 */
class Edit extends Controller
{
    /**
     * @param string|null $uuid
     * @return Application|Factory|View
     */
    public function index(string $uuid = null)
    {
        return view('administrator.content.edit', [
            'uuid' => $uuid ?? Uuid::uuid4()->toString(),
            'object' => $object = Navigation::where(['uuid' => $uuid])->first(),
            'routes' => [
                'store' => 'admin.navigation.store'
            ],
            'back' => [
                'route' => $uuid
                    ? route('admin.navigation.items.home', ['uuid' => $uuid])
                    : route('admin.navigation.home'),
                'text' => __('navigation.back')
            ],
            'chunks_template' => [
                [
                    'icon' => '<i class="fas fa-cog me-1"></i>',
                    'key' => 'general',
                    'name' => __('navigation.tabs.general'),
                    'template' => 'administrator.navigation.partials.general',
                    'data' => [
                        'templates' => $this->getTemplates() ?? ['Default']
                    ]
                ]
            ],
            'breadcrumbs_data' => [
                'navigation' => $object
            ],
            'title_create' => __('navigation.create')
        ]);
    }

    /**
     * Шаблоны для навигаций
     * @return array
     */
    private function getTemplates(): array
    {
        $templates_path = resource_path('views/web/components/navigation');
        $templates = glob($templates_path . '/*.blade.php');

        $template_file_name = [];
        if ($templates) {
            foreach ($templates as $template) {
                $template_file_name[] = str_replace('.blade.php', '', basename($template));
            }
        }

        return $template_file_name;
    }
}
