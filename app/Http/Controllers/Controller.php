<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Prospect Api Demo Documentation",
     *      description="Prospect API demo description",
     *      @OA\Contact(
     *          email="hai.vu@twentyci.asia"
     *      ),
     *      @OA\License(
     *          name="TwentyTech",
     *          url="https://www.twentyci.co.uk/"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * )

     *
     * @OA\Tag(
     *     name="Prospect",
     *     description="API Endpoints of Projects"
     * )
     *
     * /**
     * @OA\Get(
     *     path="/",
     *     description="Home page",
     *     @OA\Response(response="default", description="Welcome page")
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
