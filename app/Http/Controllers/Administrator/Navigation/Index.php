<?php

namespace App\Http\Controllers\Administrator\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Navigation;
use Illuminate\Http\Request;

/**
 * Список доступных меню
 * @package App\Http\Controllers\Administrator\Navigation
 */
class Index extends Controller
{
    public function index()
    {
        return view('administrator.content.index', [
            'object' => null,
            'object_list' => Navigation::all(),
            'nested_view_name' => 'navigation',
            'breadcrumbs_data' => [],
            'header' => [
                'icon' => null,
                'title' => __('navigation.title'),
                'create' => __('navigation.create')
            ],
            'routes' => [
                'sortable' => null,
                'create' => route('admin.navigation.edit'),
                'home' => 'admin.navigation.home',
                'edit' => 'admin.navigation.edit',
                'delete' => 'admin.navigation.delete',
            ]
        ]);
    }
}
