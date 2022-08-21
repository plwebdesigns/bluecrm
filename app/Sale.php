<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    // Available for mass assignment
    protected $fillable = [
        'id',
        'type',
        'agent_name',
        'client_name',
        'address',
        'city',
        'closing_date',
        'sale_price',
        'total_commission',
        'blue_profit',
        'transaction_fee',
        'mortgage_choice',
        'title_choice',
        'notes',
        'created_at',
        'updated_at',
        'user',
        'office_location'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot([
                'split',
                'commission',
                'percent_of_sale',
                'sale_credit',
                'blue_credit',
                'transaction_credit',
                'split_sale'
            ]);
    }
}
