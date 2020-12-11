<?php

namespace App\Models;

use Laravel\Cashier\Billable as CashierBillable;

trait Billable
{
    use CashierBillable;

    public function paymentMethods()
    {
        return $this->morphMany(PaymentMethod::class, 'payment_methodable');
    }

    public function defaultPaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'default_payment_method_id');
    }

    public function hasDefaultPaymentMethod()
    {
        return (bool) $this->default_payment_method_id;
    }

    public function cardPaymentMethods()
    {
        return $this->paymentMethods()->where('type', 'card');
    }

    public function updateCardPaymentMethod($stripePaymentMethodId)
    {
        $this->deleteCardPaymentMethods();

        $this->assertCustomerExists();

        $paymentMethod = $this->addPaymentMethod($stripePaymentMethodId);

        $cardPaymentMethod = $this->cardPaymentMethods()->create([
            'type' => 'card',
            'stripe_id' => $paymentMethod->id,
            'card_brand' => $paymentMethod->card->brand,
            'card_last_four' => $paymentMethod->card->last4,
        ]);

        if (! $this->hasDefaultPaymentMethod()) {
            $this->default_payment_method_id = $cardPaymentMethod->id;
            $this->save();
        }
    }

    public function deleteCardPaymentMethods()
    {
        $this->cardPaymentMethods->each(function ($cardPaymentMethod) {
            $this->removePaymentMethod($cardPaymentMethod->stripe_id);

            if ($cardPaymentMethod->id == $this->default_payment_method_id) {
                $this->default_payment_method_id = null;
                $this->save();
            }

            $cardPaymentMethod->delete();
        });
    }
}
