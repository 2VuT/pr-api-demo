<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchStripeController extends Controller
{
    /**
     * Get the given branch's Stripe token.
     *
     * @param  Request  $request
     * @param  App\Models\Branch  $branch
     * @return Response
     */
    public function getToken(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        return [
            'clientSecret' => $branch->createSetupIntent()->client_secret,
        ];
    }
}
