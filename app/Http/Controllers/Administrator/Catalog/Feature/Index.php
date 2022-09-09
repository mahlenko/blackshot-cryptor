<?php

namespace App\Http\Controllers\Administrator\Catalog\Feature;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Feature;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
        return view('administrator.catalog.feature.index', [
            'features' => Feature::defaultOrder()->with(['translations', 'group', 'products'])->paginate()
        ]);

    }
}
