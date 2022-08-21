<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AddSaleController extends Controller
{
    // This is where a user with admin privileges can go to create
    // a new sale
    public function create(){
        $types = DB::table('type_of_sale')->pluck('type');
        $cities = DB::table('cities')->pluck('place_name')->sort();
        $mortgages = DB::table('mortgage_names')->pluck('mortgage_names');
        $titles = DB::table('title_names')->pluck('title_names');
        $agents = User::all()->pluck('agent_name')->sort();
        $office_locations = DB::table('office_names')->pluck('office_name')->sort();

        $fields = [
            ['name' => 'office_location', 'type' => 'select', 'options' => $office_locations],
            ['name' => 'type', 'type' => 'select', 'options' => $types],
            ['name' => 'client_name', 'type' => 'text', 'placeholder' => 'John Smith'],
            ['name' => 'address', 'type' => 'text', 'placeholder' => '123 Main Street'],
            ['name' => 'city', 'type' => 'select', 'options' => $cities],
            ['name' => 'closing_date', 'type' => 'date'],
            ['name' => 'sale_price', 'type' => 'number', 'placeholder' => '500000'],
            ['name' => 'lender', 'type' => 'select', 'options' => $mortgages],
            ['name' => 'title_choice', 'type' => 'select', 'options' => $titles],
            ['name' => 'total_commission', 'type' => 'number', 'placeholder' => '5000'],
            ['name' => 'transaction_fee', 'type' => 'number', 'placeholder' => '500'],
            ['name' => 'blue_profit', 'type' => 'number', 'placeholder' => '500'],
            ['name' => 'notes', 'type' => 'textarea', 'placeholder' => 'Enter notes here...']
        ];

        $segment = [
            ['agent' => '', 'split' => '', 'commission' => '', 'membership_dues_paid' => ''],
            ['agent' => '', 'split' => '', 'commission' => '', 'membership_dues_paid' => ''],
            ['agent' => '', 'split' => '', 'commission' => '', 'membership_dues_paid' => ''],
            ['agent' => '', 'split' => '', 'commission' => '', 'membership_dues_paid' => ''],
            ['agent' => '', 'split' => '', 'commission' => '', 'membership_dues_paid' => ''],
            ['agent' => '', 'split' => '', 'commission' => '', 'membership_dues_paid' => '']
        ];

        $form = [
            'fields' => $fields
        ];

        return view(
            'admin.add_sale',
            ['form' => $form, 'agents' => $agents, 'segment' => $segment]
        );
    }

    // Called when form submitted to create a new sale
    public function store(Request $request)
    {
        $sale = $request->input();
        $rules = [
            'office_location' => 'required',
            'type' => 'required',
            'client_name' => 'required|max:200',
            'address' => 'required|max:200',
            'city' => 'required',
            'closing_date' => 'required|date',
            'sale_price' => 'required|numeric',
            'total_commission' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'blue_profit' => 'required|numeric',
            'lender' => 'required',
            'title_choice' => 'required',
        ];
        // Validate sale and commission segments
        $validator = Validator::make($sale, $rules);
        $validator->after(function ($validator) use ($sale){
            // Initialize variables
            $split_total = 0;
            $credit_total = 0;
            $commissions = 0;
            $total_sale_commission = ($sale['total_commission'] > 0) ? $sale['total_commission'] : 0;

            foreach ($sale['segment'] as $segment){
                if (isset($segment['split'])){
                    $split_total += $segment['split'];
                }
                if ($segment['percent_of_sale'] > 0) {
                    $credit_total += $segment['percent_of_sale'];
                }
                if($segment['commission'] > 1){
                    $commissions += $segment['commission'];
                }
            }
            if ($split_total > 100){
                $validator->errors()->add('segment', 'Split for all agents adds up to more than 100%');
            }
            if ($credit_total > 100) {
                $validator->errors()->add('segment', 'Sale Credit for all agents adds up to more than 100%');
            }
            if ($commissions > $total_sale_commission) {
                $validator->errors()->add('segment', 'Total commission is $' . $total_sale_commission . ' which is less than commission of both agents $' . $commissions);
            }
        });
        if ($validator->fails()){
             return back()->withErrors($validator->errors())->withInput();
	    }
        // Remove null commission segments
        $commission_segments = array_filter($sale['segment'], function ($val){
            return $val['agent'] !== null;
        });
        // Remove commission segments from sale, create sale then save sale to DB
        unset($sale['segment']);
        // set correct name lender -> mortgage_choice
        $sale['mortgage_choice'] = $sale['lender'];
        unset($sale['lender']);
        $new_sale = new Sale($sale);
        $new_sale->save();
        // Add each commission segment to the sale
        foreach ($commission_segments as $segment) {
            $user = DB::table('users')->where('agent_name', $segment['agent'])->first();
            $segment['user_id'] = $user->id;
            $user->membership_dues_paid += $segment['membership_dues_paid'];
            $segment['percent_of_sale'] = $segment['percent_of_sale']/100;
            $segment['sale_credit'] = $new_sale->sale_price * $segment['percent_of_sale'];
            $segment['blue_credit'] = $new_sale->blue_profit * $segment['percent_of_sale'];
            $segment['transaction_credit'] = $new_sale->transaction_fee * $segment['percent_of_sale'];
            $segment['created_at'] = Date::now('EST');
            $segment['updated_at'] = Date::now('EST');
            if ($segment['split'] > 1) {
                $segment['split'] = $segment['split']/100;
            }
            // Remove non sale_user fields after calculation
            unset($segment['agent']);
            unset($segment['membership_dues_paid']);
            $new_sale->users()->attach($new_sale->id, $segment);
        }
        $prev_url = $request->session()->get('_previous.url');
        return view('admin.success', ['prev_url' => $prev_url]);
    }
} // End Class
