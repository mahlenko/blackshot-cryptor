<?php

namespace App\Http\Controllers\Administrator\References\Country;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index()
    {
        return view('administrator.references.country.index', [
            'countries' => Country::defaultOrder()->paginate(20)
        ]);
    }
}
