<?php

namespace App\Contracts\Repositories;

use App\Models\Branch;

interface PropertyRepository
{
    /**
     * Get all of New Instruction On Market Sale audiences for a given branch.
     *
     * @param  \App\Models\Branch  $branch
     * @param  array  $options
     * @return mixed
     */
    public function newInstructionsOnMarketSaleAudiences(Branch $branch, $options = []);

    /**
     * Get all of Withdrawn On Market Sale audiences for a given branch.
     *
     * @param  \App\Models\Branch  $branch
     * @param  array  $options
     * @return mixed
     */
    public function withdrawnOnMarketSaleAudiences(Branch $branch, $options = []);

    /**
     * Get all of Fall Through On Market Sale audiences for a given branch.
     *
     * @param  \App\Models\Branch  $branch
     * @param  array  $options
     * @return mixed
     */
    public function fallThroughOnMarketSaleAudiences(Branch $branch, $options = []);
}
