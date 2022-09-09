<?php

namespace App\Http\Controllers\Administrator\Catalog\FeatureGroup;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Catalog\FeatureGroup;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
        return view('administrator.catalog.feature-group.index', [
            'groups' => FeatureGroup::defaultOrder()->with(['translations', 'features'])->paginate()
        ]);
    }
}
