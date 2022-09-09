<?php

namespace App\Http\Controllers\Administrator\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
        return view('administrator.content.index', [
            'object' => null,
            'object_list' => User::paginate(),
            'nested_view_name' => 'users',
            'header' => [
                'title' => __('user.title')
            ],
            'routes' => [
                'edit' => 'admin.user.edit',
                'delete' => 'admin.user.delete'
            ]
        ]);
    }
}
