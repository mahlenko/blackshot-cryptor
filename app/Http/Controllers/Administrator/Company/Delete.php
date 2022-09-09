<?php

namespace App\Http\Controllers\Administrator\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'uuid' => 'required|uuid|exists:App\Models\Company\Company,uuid'
        ]);

        $company = Company::find($data['uuid']);
        $company_name = $company->name;
        $result = (new CompanyRepository())->delete($data['uuid']);

        if ($result) {
            flash(__('messages.success.delete', ['name' => $company_name]))->success();
        } else {
            flash(__('messages.fail.delete'))->error();
        }

        return redirect()->route('admin.company.home');
    }
}
