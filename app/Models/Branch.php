<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    use Billable;
    use HasCredits;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = ['districts', 'subscriptions', 'branchCredit', 'cardPaymentMethods', 'defaultPaymentMethod'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all of the users that belong to the branch.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function districts()
    {
        return $this->belongsToMany(District::class)
            ->withTimestamps();
    }

    public function suppressedProperties()
    {
        return $this->hasMany(BranchSuppressedProperty::class);
    }

    public function suppressedBrands()
    {
        return $this->hasMany(BranchSuppressedBrand::class);
    }

    public function postcodeDistricts()
    {
        return $this->districts()->pluck('postcode')->all();
    }
}
