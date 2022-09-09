<?php

namespace App\Http\Controllers\Administrator\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Http\Request;

class Index extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('administrator.company.index', [
            'companies' => Company::defaultOrder()->with(['address', 'translations'])->paginate()
        ]);
    }
}
