<?php

namespace App\Models;

trait HasCredits
{
    public function branchCredit()
    {
        return $this->hasOne(BranchCredit::class);
    }

    public function purchaseCreditBundle(CreditBundle $creditBundle)
    {
        $this->charge($creditBundle->cost, $this->defaultPaymentMethod->stripe_id);

        return $this->addCredits($creditBundle->number_of_credits);
    }

    public function addCredits($numberOfCredits)
    {
        if ($this->branchCredit) {
            $this->branchCredit->balance = $numberOfCredits + $this->branchCredit->balance;
            $this->branchCredit->save();
        } else {
            $this->branchCredit()->save(new BranchCredit([
                'balance' => $numberOfCredits,
            ]));
        }

        return $this;
    }
}
