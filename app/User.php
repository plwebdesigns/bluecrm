<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_name',
        'email',
        'password',
        'api_token',
        'isAdmin',
        'id',
        'added_by',
        'phone',
        'title',
        'membership_dues_paid',
        'membership_dues_owed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot([
            'split',
            'percent_of_sale',
            'commission',
            'sale_credit',
            'blue_credit',
            'transaction_credit',
            'split_sale'
        ]);
    }
}
