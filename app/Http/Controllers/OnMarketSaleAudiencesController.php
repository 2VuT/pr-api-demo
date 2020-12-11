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
