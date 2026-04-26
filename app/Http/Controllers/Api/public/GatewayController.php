<?php

namespace Vpn\App\Http\Controllers\Api\public;

use Illuminate\Http\Request;
use Vpn\App\Services\KeysGenerator;
use Elyerr\ApiResponse\Exceptions\ReportError;

class GatewayController
{

    public $model;

    public function __construct(KeysGenerator $keyGenerator)
    {
        $this->model = $keyGenerator;
    }

    /**
     * Validate token __invoke
     * @param Request $request
     * @throws ReportError
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $token = $request->header('Authorization');

        if (!empty($token) && $this->model->validateToken($token)) {
            return  response()->noContent(204);
        }

        throw new ReportError(__('Invalid credentials'), 401);
    }
}
