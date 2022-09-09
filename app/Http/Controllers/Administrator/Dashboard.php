<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Finder\File;
use App\Models\Meta;
use App\Models\Page\Page;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index()
    {
        return view('administrator.dashboard.index', [
            'categories' => Category::count(),
            'products' => Product::count(),
            'pages' => Page::count(),
            'pages_views' => Meta::where(['object_type' => Page::class])->sum('views'),
            'users' => User::count(),
            'administrators' => User::where(['role' => User::ROLE_ADMIN])->count(),
            'files' => File::count(),
            'downloads' => File::sum('downloads'),
        ]);
    }
}
