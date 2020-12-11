<?php

namespace Tests\Feature;

use App\Models\District;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase, InteractsWithPaymentProviders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_branches_can_subscribe()
    {
        Sanctum::actingAs(
            $user = $this->createBranchUser($branch = $this->createBranchWithCardPaymentMethod([
                District::create(['postcode' => 'MK1']),
                District::create(['postcode' => 'MK2']),
            ]))
        );

        $response = $this->json('POST', '/api/branches/'.$branch->id.'/subscription', [
            'stripe_plan' => env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'),
        ]);

        $response->assertOk();

        $this->assertTrue($branch->subscribedToPlan(env('TEST_PLAN_PROSPECT_PLUS_MONTHLY'), 'default'));
        $this->assertEquals(2, $branch->subscription('default')->quantity);
    }
}
