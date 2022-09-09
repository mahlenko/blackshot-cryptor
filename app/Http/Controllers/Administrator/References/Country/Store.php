<?php

namespace App\Http\Controllers\Administrator\References\Country;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\ObjectLocaleRequest;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class Store extends Controller
{
    public function index(ObjectLocaleRequest $object_locale, CountryRequest $request)
    {
        $object = $object_locale->validated();
        $data = $request->validated();

        try {
            $country = (new CountryRepository())->store(
                $object['locale'],
                $object['uuid'],
                $data['name'],
                $data['alpha2'] ?? null,
                $data['alpha3'] ?? null,
            );
        } catch (InvalidArgumentException $exception) {
            flash($exception->getMessage())->error();
            return back()->withInput();
        }

        flash(__('messages.success.save', ['name' => $country->name]))->success();
        return redirect()->route('admin.references.country.edit', ['uuid' => $country->uuid, 'locale' => $object['locale']]);
    }
}
