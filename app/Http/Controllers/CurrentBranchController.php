<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class CurrentBranchController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()->currentBranch;
    }

    /**
     * Update the authenticated user's current branch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $branch = Branch::findOrFail($request->branch_id);

        if (! $request->user()->switchBranch($branch)) {
            abort(403);
        }

        return $branch;
    }
}
