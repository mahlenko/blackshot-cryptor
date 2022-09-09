<?php

namespace App\Http\Controllers\Administrator\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Edit extends Controller
{
    public function index(int $id)
    {
        return view('administrator.content.edit', [
            'uuid' => null,
            'object' => $object = User::where(['id' => $id])->firstOrFail(),
            'breadcrumbs_data' => ['object' => $object],
            'back' => [
                'route' => route('admin.user.home')
            ],
            'routes' => [
                'store' => 'admin.user.store'
            ],
            'chunks_template' => [
                [
                    'icon' => '<i class="fas fa-cog me-1"></i>',
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.user.general',
                    'data' => []
                ]
            ]
        ]);
    }
}
