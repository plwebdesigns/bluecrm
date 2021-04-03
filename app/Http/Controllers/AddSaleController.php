<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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

        $fields = [
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
            ['name' => 'membership_dues', 'type' => 'number', 'placeholder' => '50']
        ];

        $segment = [
            ['agent' => '', 'split' => '', 'commission' => ''],
            ['agent' => '', 'split' => '', 'commission' => ''],
            ['agent' => '', 'split' => '', 'commission' => ''],
            ['agent' => '', 'split' => '', 'commission' => ''],
            ['agent' => '', 'split' => '', 'commission' => ''],
            ['agent' => '', 'split' => '', 'commission' => '']
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
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $sale = $request->input();

    //    dd($sale);
        $rules = [
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
            'segment.0.agent' => 'required',
            'segment.*.split' => 'numeric|max:100|nullable'
        ];
        $messages = [
            'segment.0.agent.required' => 'At lease one agent should be selected',
            'segment.*.split.max' => 'Split cannot be greater than 100%'
        ];
        $validator = Validator::make($sale, $rules, $messages);
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
//            dd(back()->withErrors($validator->errors())->withInput());
             return back()->withErrors($validator->errors())->withInput();
        }
        return redirect()->route('success_add_sale', ['sale' => $sale]);
        

    }
}
