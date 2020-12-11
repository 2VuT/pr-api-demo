<?php

namespace App\Contracts\Repositories;

use App\Models\Branch;

interface BranchRepository
{
    /**
     * Get all of suppressed UPRNs for a given branch.
     *
     * @param  \App\Models\Branch  $branch
     * @return array
     */
    public function suppressedUPRNs(Branch $branch);
}
