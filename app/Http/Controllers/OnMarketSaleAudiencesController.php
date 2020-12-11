<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\PropertyRepository;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnMarketSaleAudiencesController extends Controller
{
    /**
     * The properties repository.
     *
     * @var PropertyRepository
     */
    protected $properties;

    /**
     * Create a new controller instance.
     *
     * @param  PropertyRepository  $properties
     * @return void
     */
    public function __construct(PropertyRepository $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @OA\Get(
     *      path="/audiences/on-market-sale",
     *      operationId="getOnMarketSaleAudiencesList",
     *      tags={"On market sale audiences"},
     *      summary="Get list of on market sale",
     *      description="Returns list of on market sale",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Bad request",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(Request $request)
    {
        $method = Str::camel($request->trigger).'OnMarketSaleAudiences';

        if (! method_exists($this->properties, $method)) {
            abort(404);
        }

        $branch = Branch::findOrFail($request->branch_id);

        if (! $request->user()->belongsToBranch($branch)) {
            abort(403);
        }

        return $this->properties->{$method}($branch);
    }
}
