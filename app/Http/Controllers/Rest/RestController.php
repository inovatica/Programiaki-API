<?php

namespace App\Http\Controllers\Rest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RestController extends Controller
{
    /**
     * @SWG\Swagger(
     *   basePath="/api",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *   @SWG\Info(
     *      title="Programiaki API",
     *      version="0.1"
     *  ),
     *  @SWG\SecurityScheme(
     *     securityDefinition="Bearer",
     *     type="apiKey",
     *     in="header",
     *     name="Authorization"
     *  )
     * )
     */
}
