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
        $types = DB::table('type_of_sale')->get('type')->toArray();
        $arr_types = [];
        foreach ($types as $type){
            array_push($arr_types, $type->type);
        }
        $cities = DB::table('cities')->get('place_name');
        $cities_arr = [];
        foreach ($cities as $key => $value) {
            array_push($cities_arr, $value->place_name);
        }
        $mortgages = DB::table('mortgage_names')->get('mortgage_names');
        $mortgages_arr = [];
        foreach ($mortgages as $value) {
            array_push($mortgages_arr, $value->mortgage_names);
        }
        $titles = DB::table('title_names')->get('title_names');
        $titles_arr = [];
        foreach ($titles as $value) {
            array_push($titles_arr, $value->title_names);
        }
        $agents = User::all();
        $agents = $agents->map(function ($value){
            return $value->agent_name;
        })->sort();

        $fields = [
            ['name' => 'type', 'type' => 'select', 'options' => $arr_types],
            ['name' => 'client_name', 'type' => 'text'],
            ['name' => 'address', 'type' => 'text'],
            ['name' => 'city', 'type' => 'select', 'options' => $cities_arr],
            ['name' => 'closing_date', 'type' => 'date'],
            ['name' => 'sale_price', 'type' => 'text'],
            ['name' => 'lender', 'type' => 'select', 'options' => $mortgages_arr],
            ['name' => 'title_choice', 'type' => 'select', 'options' => $titles_arr],
            ['name' => 'total_commission', 'type' => 'text'],
            ['name' => 'transaction_fee', 'type' => 'text'],
            ['name' => 'blue_profit', 'type' => 'text'],
            ['name' => 'membership_dues', 'type' => 'text']
        ];

        $segment = [
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

//        dd($sale);
        $rules = [
            'type' => 'required',
            'client_name' => 'required|string|min:3|max:200',
            'address' => 'required|string|min:3|max:200',
            'city' => 'required',
            'closing_date' => 'required|date',
            'sale_price' => 'required|numeric',
            'total_commission' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'blue_profit' => 'required|numeric',
            'lender' => 'required',
            'title_choice' => 'required',
            'segment' => 'required|array|min:1',
            'segment.*.name' => 'required'
        ];
        $messages = [
            'segment.*.name.required' => 'Please select an agent'
        ];
        $validator = Validator::make($sale, $rules, $messages);

        if ($validator->fails()){
//            dd(back()->withErrors($validator->errors())->withInput());
            return back()->withErrors($validator->errors())->withInput();
        }

    }
}
