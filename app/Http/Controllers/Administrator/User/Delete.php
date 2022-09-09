<?php

namespace App\Http\Controllers\Administrator\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Delete extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'id' => 'required'
        ]);

        $user = User::find($data['id']);

        if (Auth::user()->isAdmin() && Auth::user()->created < $user->created_at) {

            $user_name = $user->name;
            $user->delete();

            flash(__('messages.success.delete', ['name' => $user_name]))->success();
            return back();
        }
    }
}
