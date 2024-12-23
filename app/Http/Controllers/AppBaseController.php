<?php

namespace App\Http\Controllers;

use App\Services\ResponseBuilderService\ResponseBuilder;

class AppBaseController extends Controller
{
    protected ResponseBuilder $response;

    public function __construct()
    {
        $responseApiClient = new ResponseBuilder();
        $this->response = $responseApiClient;
    }
}
