<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCredit;
use App\Models\CreditBundle;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Stripe\Exception\InvalidRequestException;

class BranchCreditsController extends Controller
{
    public function store(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $this->validate($request, [
            'credit_bundle_id' => [
                'required',
            ],
        ]);

        $creditBundle = CreditBundle::find($request->credit_bundle_id);

        if (! $creditBundle) {
            throw ValidationException::withMessages([
                'credit_bundle_id' => ['The selected credit bundle id is invalid.'],
            ]);
        }

        try {
            return $branch->purchaseCreditBundle($creditBundle);
        } catch (InvalidRequestException $e) {
            return response([
                'credit_bundle_id' => [$e->getMessage()],
            ], 422);
        }
    }
}
