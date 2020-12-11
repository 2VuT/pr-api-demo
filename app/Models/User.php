<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->first_name.' '.$this->last_name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get all of the brands the user belongs to.
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all of the branches the user belongs to.
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Determine if the user belongs to the given brand.
     *
     * @param  mixed  $brand
     * @return bool
     */
    public function belongsToBrand($brand)
    {
        return $this->brands->contains(function ($b) use ($brand) {
            return $b->id === $brand->id;
        });
    }

    /**
     * Determine if the user belongs to the given branch.
     *
     * @param  mixed  $branch
     * @return bool
     */
    public function belongsToBranch($branch)
    {
        return $this->belongsToBrand($branch->brand) || $this->branches->contains(function ($b) use ($branch) {
            return $b->id === $branch->id;
        });
    }

    /**
     * Determine if the given branch is the current branch.
     *
     * @param  mixed  $branch
     * @return bool
     */
    public function isCurrentBranch($branch)
    {
        return $branch->id === $this->currentBranch->id;
    }

    /**
     * Get the current branch of the user's context.
     */
    public function currentBranch()
    {
        return $this->belongsTo(Branch::class, 'current_branch_id');
    }

    /**
     * Switch the user's context to the given branch.
     *
     * @return bool
     */
    public function switchBranch($branch)
    {
        if (! $this->belongsToBranch($branch)) {
            return false;
        }

        $this->forceFill([
            'current_branch_id' => $branch->id,
        ])->save();

        $this->setRelation('currentBranch', $branch);

        return true;
    }
}
