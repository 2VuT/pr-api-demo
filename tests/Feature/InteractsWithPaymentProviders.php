<?php

namespace Tests\Feature;

use App\Models\Branch;
use Stripe\PaymentMethod;
use Stripe\Stripe;

trait InteractsWithPaymentProviders
{
    public function createBranchWithCardPaymentMethod($districts = [])
    {
        $branch = Branch::factory()->create();

        if (! empty($districts)) {
            $branch->districts()->saveMany($districts);
        }

        $branch->createOrGetStripeCustomer();

        $branch->updateCardPaymentMethod($this->createCardPaymentMethod());

        return $branch;
    }

    public function createSubscribedBranch($plan, $districts = [])
    {
        $branch = $this->createBranchWithCardPaymentMethod($districts);

        $branch
            ->newSubscription('default', $plan)
            ->quantity($branch->districts->count())
            ->create($branch->defaultPaymentMethod->stripe_id);

        return $branch;
    }

    protected function createCardPaymentMethod()
    {
        Stripe::setApiKey(config('cashier.secret'));

        return PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 5,
                'exp_year' => 2021,
                'cvc' => '314',
            ],
        ]);
    }
}
