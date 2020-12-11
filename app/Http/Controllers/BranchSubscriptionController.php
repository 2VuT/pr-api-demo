<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Stripe\Exception\InvalidRequestException;

class BranchSubscriptionController extends Controller
{
    /**
     * Subscribe the branch to a new plan.
     *
     * @param  Request  $request
     * @param  App\Models\Branch  $branch
     * @return Response
     */
    public function store(Request $request, Branch $branch)
    {
        try {
            $branch
                ->newSubscription('default', $request->stripe_plan)
                ->quantity($branch->districts->count())
                ->create($branch->defaultPaymentMethod->stripe_id);
        } catch (InvalidRequestException $e) {
            return response([
                'stripe_plan' => [$e->getMessage()],
            ], 422);
        }
    }

    /**
     * Change the branch's subscription plan.
     *
     * @param  Request  $request
     * @param  App\Models\Branch  $branch
     * @return Response
     */
    public function update(Request $request, Branch $branch)
    {
        try {
            $branch
                ->subscription('default')
                ->swap([
                    $request->stripe_plan => ['quantity' => $branch->districts->count()],
                ]);
        } catch (InvalidRequestException $e) {
            return response([
                'stripe_plan' => [$e->getMessage()],
            ], 422);
        }
    }

    /**
     * Cancel the branch's subscription.
     *
     * @param  Request  $request
     * @param  App\Models\Branch  $branch
     * @return Response
     */
    public function destroy(Request $request, Branch $branch)
    {
        try {
            $branch->subscription('default')->cancel();
        } catch (InvalidRequestException $e) {
            return response([
               'message' => [$e->getMessage()],
            ], 422);
        }
    }
}
