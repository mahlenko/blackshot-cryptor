<?php

namespace App\Http\Controllers\Administrator\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\MetaRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Models\Company\Company;
use App\Repositories\CompanyRepository;
use App\Rules\LocaleRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Store extends Controller
{
    /**
     * @param CompanyRequest $company
     * @param MetaRequest $meta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(
        ObjectLocaleRequest $object,
        CompanyRequest $companyRequest,
        MetaRequest $metaRequest
    ): \Illuminate\Http\RedirectResponse
    {
        $object = $object->validated();
        $company_data = $companyRequest->validated();
        $meta_data = $metaRequest->validated()['meta'];
        $meta_data['class'] = Company::class; /* обязательно добавлять */

        /* сохраним компанию */
        $company = (new CompanyRepository())
            ->store($object['uuid'], $object['locale'], $company_data, $meta_data);

        if ($company) {
            flash(__('messages.success.save', [
                'name' => $company_data['name']
            ]))->success();
        } else {
            flash(__('messages.fail.save'))->error();
        }

        return redirect()->route('admin.company.edit', $object);
    }
}
