<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase, InteractsWithPaymentProviders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_branch_card_payment_method_can_be_updated()
    {
        Sanctum::actingAs($user = $this->createBranchUser($branch = Branch::factory()->create()));

        $paymentMethod = $this->createCardPaymentMethod();

        $response = $this->json('PUT', '/api/branches/'.$branch->id.'/card-payment-method', [
            'stripe_payment_method' => $paymentMethod->id,
        ]);

        $response->assertOk();

        $branch = $branch->fresh();

        $this->assertCount(1, $branch->cardPaymentMethods);
        $this->assertInstanceOf(PaymentMethod::class, $branch->cardPaymentMethods[0]);
        $this->assertEquals($paymentMethod->id, $branch->cardPaymentMethods[0]->stripe_id);
        $this->assertEquals($branch->default_payment_method_id, $branch->cardPaymentMethods[0]->id);
    }

    public function test_branch_card_payment_method_can_be_removed()
    {
        Sanctum::actingAs($user = $this->createBranchUser($branch = $this->createBranchWithCardPaymentMethod()));

        $this->assertCount(1, $branch->fresh()->cardPaymentMethods);

        $response = $this->json('DELETE', '/api/branches/'.$branch->id.'/card-payment-method');

        $response->assertOk();

        $branch = $branch->fresh();

        $this->assertCount(0, $branch->cardPaymentMethods);
        $this->assertEquals(null, $branch->default_payment_method_id);
    }
}
