<?php

namespace Tests\Feature;

use App\Models\District;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CancelSubscriptionTest extends TestCase
{
    use RefreshDatabase, InteractsWithPaymentProviders;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_subscription_can_be_cancelled()
    {
        Sanctum::actingAs(
            $user = $this->createBranchUser($branch = $this->createSubscribedBranch(env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'), [
                District::create(['postcode' => 'MK1']),
            ]))
        );

        $response = $this->json('DELETE', '/api/branches/'.$branch->id.'/subscription');

        $response->assertOk();

        $this->assertTrue($branch->fresh()->subscription('default')->onGracePeriod());
    }
}
