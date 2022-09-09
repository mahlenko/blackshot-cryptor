<?php

namespace App\Http\Controllers\Administrator\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
//        return view('administrator.catalog.index', [
//            'tabs' => []
//        ]);

        return redirect()->route('admin.catalog.category.home');
    }
}
