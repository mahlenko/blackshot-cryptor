<?php

namespace App\Http\Controllers\Administrator\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Stores\UserStore;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Store extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:\App\Models\User,id',
            'name' => 'required',
            'email' => 'required|email',
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
            'new_password' => 'nullable|confirmed',
            'avatar' => 'nullable:image'
        ]);

        try {
            $store = (new UserStore())->handle($data);

            if ($store) {
                flash(__('messages.success.save', ['name' => $data['name']]))->success();
                return redirect()->route('admin.user.home');
            }

            flash(__('messages.fail.save'))->error();
            return redirect()->back()->withInput($data);
        } catch (DomainException $e) {
            /* произошли ошибки при проверках */
            flash($e->getMessage())->error();
            return back()->withInput();
        }
    }
}
