<?php

namespace App\View\Components\Administrator;

use Illuminate\View\Component;

class Navigation extends Component
{
    public $navigations = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->navigations = [
            [
                'icon' => '<i class="fas fa-tachometer-alt"></i>',
                'text' => __('dashboard.title'),
                'route_name' => 'admin.home'
            ],
//            [
//                'icon' => '<i class="fas fa-sitemap"></i>',
//                'text' => __('admin.website'),
//                'route_name' => 'admin.page.home',
//                'children' => [
//                    'title' => '',
//                    'items' => $this->getWebsiteMenu()
//                ]
//            ],
            [
                'icon' => '<i class="far fa-newspaper"></i>',
                'text' => __('page.title'),
                'route_name' => 'admin.page.home'
            ],
            [
                'icon' => '<i class="fas fa-bars"></i>',
                'text' => __('navigation.title'),
                'route_name' => 'admin.navigation.home'
            ],
            [
                'icon' => '<i class="fas fa-layer-group"></i>',
                'text' => __('widget.title'),
                'route_name' => 'admin.widget.home'
            ],
            [
                'icon' => '<i class="fas fa-shopping-basket"></i>',
                'text' => __('catalog.title'),
                'route_name' => 'admin.catalog.home',
                'children' => $this->getCatalogMenu()
            ],
            [
                'icon' => '<i class="far fa-building"></i>',
                'text' => __('company.title'),
                'route_name' => 'admin.company.home'
            ],
            [
                'icon' => '<i class="fas fa-cog"></i>',
                'text' => __('settings.title'),
                'route_name' => 'admin.setting.website.home',
                'route_data' => [
                    'locale' => app()->getLocale()
                ]
            ],
            [
                'icon' => '<i class="fas fa-user-friends"></i>',
                'text' => __('user.title'),
                'route_name' => 'admin.user.home'
            ],
            [
                'icon' => '<i class="fas fa-palette"></i>',
                'text' => __('template.title'),
                'route_name' => 'admin.template.home'
            ],
            [
                'icon' => '<i class="far fa-folder"></i>',
                'text' => __('finder.title'),
                'route_name' => 'admin.finder.home'
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('administrator.components.navigation');
    }

    /**
     * @return array
     */
    private function getCatalogMenu(): array
    {
        return [
//            [
//                'title' => '',
//                'items' => [
//                    [
//                        'icon' => '',
//                        'text' => __('catalog.title'),
//                        'route_name' => 'admin.catalog.home'
//                    ],
////                    [
////                        'icon' => '',
////                        'text' => __('catalog.settings'),
////                        'route_name' => 'admin.catalog.home'
////                    ],
//                ]
//            ],
            [
                'title' => __('catalog.title'),
                'items' => [
                    [
                        'icon' => '',
                        'text' => __('catalog.category.title'),
                        'route_name' => 'admin.catalog.category.home'
                    ],
                    [
                        'icon' => '',
                        'text' => __('catalog.product.title'),
                        'route_name' => 'admin.catalog.product.home'
                    ],
                ]
            ],
            [
                'title' => __('catalog.feature.title'),
                'items' => [
                    [
                        'icon' => '',
                        'text' => __('catalog.feature.group.title_breadcrumbs'),
                        'route_name' => 'admin.catalog.feature.group.home'
                    ],
                    [
                        'icon' => '',
                        'text' => __('catalog.feature.title'),
                        'route_name' => 'admin.catalog.feature.home'
                    ],
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    private function getWebsiteMenu(): array
    {
        return [
            [
                'icon' => '<i class="far fa-file-alt"></i>',
                'text' => __('page.title'),
                'route_name' => 'admin.page.home'
            ],
            [
                'icon' => '<i class="fas fa-bars"></i>',
                'text' => __('navigation.title'),
                'route_name' => 'admin.navigation.home'
            ],
            [
                'icon' => '<i class="fas fa-layer-group"></i>',
                'text' => __('widget.title'),
                'route_name' => 'admin.widget.home'
            ],
            [
                'icon' => '<i class="far fa-folder"></i>',
                'text' => __('finder.title'),
                'route_name' => 'admin.finder.home'
            ],
        ];
    }
}
