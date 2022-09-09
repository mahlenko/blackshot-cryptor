<?php

namespace App\Http\Controllers\Administrator\Navigation\Items;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Meta;
use App\Models\Navigation\Navigation;
use App\Models\Navigation\NavigationItem;
use App\Models\Page\Page;
use App\Repositories\NavigationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * Создать или редактировать ссылку
 * @package App\Http\Controllers\Administrator\Navigation\Items
 */
class Edit extends Controller
{
    /**
     * @var array
     */
    protected $navigation_types = [];

    /**
     * Edit constructor.
     */
    public function __construct()
    {
        $this->navigation_types = NavigationRepository::itemTypes();
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @param string $locale
     * @param string|null $item_uuid
     * @return Application|Factory|View
     */
    public function index(Request $request, string $uuid, string $locale, string $item_uuid = null)
    {
        $item = null;
        if ($item_uuid) {
            $item = NavigationItem::where(['uuid' => $item_uuid])->first();
            if (isset($item->meta)) {
                $request->merge(['type' => $item->meta->object_type]);
                $object_list = (new ObjectsType())->index($request)['data'];
            }
        }

        $parent_id = $item ? $item->parent_id : null;
        if ($request->has('parent_id')) {
            $parent_id = $request->input('parent_id');
        }

        if ($parent_id) {
            $parent = NavigationItem::where(['uuid' => $parent_id])->first();
        }

        return view('administrator.content.edit', [
            'locale' => $locale,
            'uuid' => $item ? $item->uuid : Uuid::uuid4()->toString(),
            'parent_id' => $parent_id,
            'parent' => $parent ?? null,
            'navigation' => $navigation = Navigation::where(['uuid' => $uuid])->first(),
            'object' => $item,
            'meta' => $meta ?? null,
            'object_list' => $object_list ?? [],
            'nested_nodes' => (new Nested(NavigationItem::class))->optGroup($locale, $item ? $item->uuid : null, [
                'navigation_uuid' => $uuid
            ]),
            'templates' => $this->getTemplates(),
            'routes' => [
                'store' => 'admin.navigation.items.store',
                'edit' => 'admin.navigation.items.edit'
            ],
            'back' => [
                'text' => __('navigation.back'),
                'route' => route('admin.navigation.items.home', [
                    'uuid' => $uuid,
                    'parent_id' => $item ? $item->parent_id : $parent_id
                ])
            ],
            'chunks_template' => $this->chunks_template(),
            'breadcrumbs_data' => [
                'navigation' => $navigation,
                'parent' => $parent ?? null,
                'item' => $item,
                'locale' => $locale
            ],
            'navigation_types' => $this->navigation_types,
            'default_class_name' => array_key_first($this->navigation_types->toArray()),
            'switcher_data' => [
                'uuid' => $uuid,
                'navigation_item' => $item_uuid,
            ],
            'title_create' => __('navigation.items.add')
        ]);
    }

    /**
     * @return array[]
     */
    private function chunks_template(): array
    {
        return [
            [
                'icon' => '<i class="fas fa-cog me-1"></i>',
                'key'  => 'general',
                'name' => __('navigation.items.tabs.general'),
                'template' => 'administrator.navigation.items.partials.general',
                'data' => []
            ],
            [
                'icon' => '<i class="fas fa-sliders-h me-1"></i>',
                'key'  => 'params',
                'name' => __('navigation.items.tabs.params'),
                'template' => 'administrator.navigation.items.partials.params',
                'data' => []
            ]
        ];
    }

    /**
     * Шаблоны для навигаций
     * @return array
     */
    private function getTemplates(): array
    {
        $templates_path = resource_path('views/web/components/navigation/items');
        $templates = glob($templates_path . '/*.blade.php');

        $clear_template_path = Str::replace(resource_path('views/'), '', $templates_path);
        $blade_string_template_path = Str::replace('/', '.', $clear_template_path);

        $template_file_name = [];
        if ($templates) {
            foreach ($templates as $template) {
                $value = str_replace('.blade.php', '', basename($template));
                $key = $blade_string_template_path .'.'. $value;

                $template_file_name[$key] = $value;
            }
        }

        return $template_file_name;
    }
}
