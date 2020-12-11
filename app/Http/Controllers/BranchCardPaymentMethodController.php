<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchCardPaymentMethodController extends Controller
{
    public function update(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $branch->createOrGetStripeCustomer();

        $branch->updateCardPaymentMethod($request->stripe_payment_method);
    }

    public function destroy(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $branch->deleteCardPaymentMethods();
    }
}
