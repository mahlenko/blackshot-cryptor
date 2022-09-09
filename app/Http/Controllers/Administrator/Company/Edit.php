<?php

namespace App\Http\Controllers\Administrator\Company;

use App\Helpers\Nested;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;

class Edit extends Controller
{
    /**
     * @param string $locale
     * @param string|null $uuid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(string $locale, string $uuid = null)
    {;
        $company = Company::find($uuid);

        if (!empty($uuid) && !$company) {
            abort(404);
        }

        return view('administrator.company.edit', [
            'locale' => $locale,
            'company' => $company,
            'nested_nodes' => (new Nested(Company::class))->optGroup(app()->getLocale(), $company->uuid ?? null),
            'types' => CompanyRepository::types(),
            'tabs' => [
                [
                    'key' => 'general',
                    'name' => __('global.tabs.general'),
                    'template' => 'administrator.company.tabs.general'
                ],
                [
                    'key' => 'contacts',
                    'name' => '<i class="far fa-address-book me-1"></i>' . __('company.tabs.contacts'),
                    'template' => 'administrator.company.tabs.contacts'
                ],
                [
                    'key' => 'meta',
                    'name' => __('meta.tab_name'),
                    'template' => 'administrator.meta.edit',
                    'data' => [
                        'object' => $company ?? null,
                        'object_type' => Company::class
                    ]
                ],
            ]
        ]);
    }
}
