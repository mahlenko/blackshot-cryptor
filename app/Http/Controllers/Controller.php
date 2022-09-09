<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    public function responseOk(string $message = null, array $data = []): JsonResponse
    {
        return $this->jsonResponse(true, $message, $data);
    }

    /**
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    public function responseFail(string $message = null, array $data = []): JsonResponse
    {
        return $this->jsonResponse(false, $message, $data);
    }

    /**
     * @param bool $ok
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    private function jsonResponse(bool $ok, string $message = null, array $data = []): JsonResponse
    {
        return response()->json(array_merge([
            'ok' => $ok,
            'message' => $message,
        ], $data));
    }

}
