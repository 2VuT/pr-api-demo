<?php

namespace Tests\Feature;

use App\Models\CreditBundle;
use Database\Seeders\CreditBundleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PurchaseCreditsTest extends TestCase
{
    use RefreshDatabase, InteractsWithPaymentProviders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->seed(CreditBundleSeeder::class);
    }

    public function test_branch_can_purchase_credits()
    {
        Sanctum::actingAs($user = $this->createBranchUser($branch = $this->createBranchWithCardPaymentMethod()));

        $creditBundle = CreditBundle::first();

        $response = $this->json('POST', '/api/branches/'.$branch->id.'/credits', [
            'credit_bundle_id' => $creditBundle->id,
        ]);

        $response->assertOk();

        $this->assertEquals($creditBundle->number_of_credits, $branch->fresh()->branchCredit->balance);
    }
}
