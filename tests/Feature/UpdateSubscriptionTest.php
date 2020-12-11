<?php

namespace Tests\Feature;

use App\Models\District;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateSubscriptionTest extends TestCase
{
    use RefreshDatabase, InteractsWithPaymentProviders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_subscription_can_be_updated()
    {
        Sanctum::actingAs(
            $user = $this->createBranchUser($branch = $this->createSubscribedBranch(env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'), [
                District::create(['postcode' => 'MK1']),
            ]))
        );

        $branch->districts()->save(District::create(['postcode' => 'MK2']));

        $response = $this->json('PUT', '/api/branches/'.$branch->id.'/subscription', [
            'stripe_plan' => env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'),
        ]);

        $response->assertOk();

        $this->assertTrue($branch->subscribedToPlan(env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'), 'default'));
        $this->assertEquals(2, $branch->subscription('default')->quantity);
    }

    public function test_plans_must_be_valid()
    {
        Sanctum::actingAs(
            $user = $this->createBranchUser($branch = $this->createSubscribedBranch(env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'), [
                District::create(['postcode' => 'MK1']),
            ]))
        );

        $response = $this->withoutExceptionHandling()
            ->json('PUT', '/api/branches/'.$branch->id.'/subscription', [
                'stripe_plan' => 'invalid-plan',
            ]);

        $response->assertStatus(422);
    }
}
