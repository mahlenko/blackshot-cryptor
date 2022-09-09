<?php

namespace App\Http\Controllers\Administrator\Widget;

use App\Http\Controllers\Controller;
use App\Models\Widget\Widget;
use Illuminate\Http\Request;

class Index extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('administrator.widget.index', [
            'widgets' => Widget::paginate()
        ]);
    }
}
