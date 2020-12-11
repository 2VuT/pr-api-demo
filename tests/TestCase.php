<?php

namespace Tests;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createBranchUser($branch = null, $attributes = [])
    {
        if (! $branch) {
            $branch = Branch::factory()->create();
        }

        $user = User::factory()->create();

        $user->branches()->attach($branch);

        $user->switchBranch($branch);

        return $user;
    }
}
