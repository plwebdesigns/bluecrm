<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use NumberFormatter;

class AdminController extends Controller {
	// Quarter Breakdown for admin page
	public function quarterBreakDown(Request $request) {
		$user = $request->user();
		$year = $request->input('production_year');
		if ($user->isAdmin && Gate::allows('create-sale', Sale::class)):

			$sales = DB::table('sales')->get();
			$quarter_one = $sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-03-31"]);
			$quarter_one_volume = $quarter_one->sum('sale_price');
			$quarter_one_units = $quarter_one->count();
			$quarter_one_sellers = $quarter_one->where('type', 'Seller')->count();
			$quarter_one_buyers = $quarter_one->where('type', 'Buyer')->count();
			$quarter_one_rentals = $quarter_one->where('type', 'Rental')->count();
			$quarter_one_referrals = $quarter_one->where('type', 'Referral')->count();
			$quarter_one_trans_fees = $quarter_one->sum('transaction_fee');
			$quarter_one_blue_profit = $quarter_one->sum('blue_profit');
			$quarter_one_total_profit = $quarter_one_blue_profit + $quarter_one_trans_fees;

			$quarter_one_summary = [
				'total_sales_volume' => $quarter_one_volume,
				'total_sellers' => $quarter_one_sellers,
				'total_buyers' => $quarter_one_buyers,
				'total_units_sold' => $quarter_one_units,
				'total_rentals' => $quarter_one_rentals,
				'total_referrals' => $quarter_one_referrals,
				'total_trans_fees' => $quarter_one_trans_fees,
				'total_blue_profit' => $quarter_one_blue_profit,
				'total_profit' => $quarter_one_total_profit,
			];

			$quarter_two = $sales->whereBetween('closing_date', ["{$year}-04-01", "{$year}-06-31"]);
			$quarter_two_volume = $quarter_two->sum('sale_price');
			$quarter_two_units = $quarter_two->count();
			$quarter_two_sellers = $quarter_two->where('type', 'Seller')->count();
			$quarter_two_buyers = $quarter_two->where('type', 'Buyer')->count();
			$quarter_two_rentals = $quarter_two->where('type', 'Rental')->count();
			$quarter_two_referrals = $quarter_two->where('type', 'Referral')->count();
			$quarter_two_trans_fees = $quarter_two->sum('transaction_fee');
			$quarter_two_blue_profit = $quarter_two->sum('blue_profit');
			$quarter_two_total_profit = $quarter_two_blue_profit + $quarter_two_trans_fees;

			$quarter_two_summary = [
				'total_sales_volume' => $quarter_two_volume,
				'total_sellers' => $quarter_two_sellers,
				'total_buyers' => $quarter_two_buyers,
				'total_units_sold' => $quarter_two_units,
				'total_rentals' => $quarter_two_rentals,
				'total_referrals' => $quarter_two_referrals,
				'total_trans_fees' => $quarter_two_trans_fees,
				'total_blue_profit' => $quarter_two_blue_profit,
				'total_profit' => $quarter_two_total_profit,
			];

			$quarter_three = $sales->whereBetween('closing_date', ["{$year}-07-01", "{$year}-09-31"]);
			$quarter_three_volume = $quarter_three->sum('sale_price');
			$quarter_three_units = $quarter_three->count();
			$quarter_three_sellers = $quarter_three->where('type', 'Seller')->count();
			$quarter_three_buyers = $quarter_three->where('type', 'Buyer')->count();
			$quarter_three_rentals = $quarter_three->where('type', 'Rental')->count();
			$quarter_three_referrals = $quarter_three->where('type', 'Referral')->count();
			$quarter_three_trans_fees = $quarter_three->sum('transaction_fee');
			$quarter_three_blue_profit = $quarter_three->sum('blue_profit');
			$quarter_three_total_profit = $quarter_three_blue_profit + $quarter_three_trans_fees;

			$quarter_three_summary = [
				'total_sales_volume' => $quarter_three_volume,
				'total_sellers' => $quarter_three_sellers,
				'total_buyers' => $quarter_three_buyers,
				'total_units_sold' => $quarter_three_units,
				'total_rentals' => $quarter_three_rentals,
				'total_referrals' => $quarter_three_referrals,
				'total_trans_fees' => $quarter_three_trans_fees,
				'total_blue_profit' => $quarter_three_blue_profit,
				'total_profit' => $quarter_three_total_profit,
			];

			$quarter_four = $sales->whereBetween('closing_date', ["{$year}-10-01", "{$year}-12-31"]);
			$quarter_four_volume = $quarter_four->sum('sale_price');
			$quarter_four_units = $quarter_four->count();
			$quarter_four_sellers = $quarter_four->where('type', 'Seller')->count();
			$quarter_four_buyers = $quarter_four->where('type', 'Buyer')->count();
			$quarter_four_rentals = $quarter_four->where('type', 'Rental')->count();
			$quarter_four_referrals = $quarter_four->where('type', 'Referral')->count();
			$quarter_four_trans_fees = $quarter_four->sum('transaction_fee');
			$quarter_four_blue_profit = $quarter_four->sum('blue_profit');
			$quarter_four_total_profit = $quarter_four_blue_profit + $quarter_four_trans_fees;

			$quarter_four_summary = [
				'total_sales_volume' => $quarter_four_volume,
				'total_sellers' => $quarter_four_sellers,
				'total_buyers' => $quarter_four_buyers,
				'total_units_sold' => $quarter_four_units,
				'total_rentals' => $quarter_four_rentals,
				'total_referrals' => $quarter_four_referrals,
				'total_trans_fees' => $quarter_four_trans_fees,
				'total_blue_profit' => $quarter_four_blue_profit,
				'total_profit' => $quarter_four_total_profit,
			];

			$summary = [
				$quarter_one_summary,
				$quarter_two_summary,
				$quarter_three_summary,
				$quarter_four_summary,
			];

			return response()->json(['summary' => $summary]);

		else:
			return response()->json(['error' => 'You are not authorized to view this content']);
		endif;
	}

	// LEADERBOARD DATA
	public function leaderboard(Request $request) {
		$year = $request->input('production_year');

		// Quarter 1 sale_credit sum per user
		$users = User::has('sales')->get();
		$quarter1Ten = [];
		$quarter2Ten = [];
		$quarter3Ten = [];
		$quarter4Ten = [];

		$ignored_agents = DB::table('ignored_agents')->get('agent_name');
		$ignored_agents->transform(function ($item) {
			return $item->agent_name;
		});

		//Get quarter 1
		foreach ($users as $user):
			$sales = $user->sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-03-31"]);
			if ($sales->isNotEmpty() && !collect($ignored_agents)->contains($user->agent_name)) {
				$quarter1Ten[] = [
					'agent' => $user->agent_name,
					'total' => collect($sales->all())->sum('pivot.sale_credit'),
				];
			}
		endforeach;
		//Get quarter 2
		foreach ($users as $user):
			$sales = $user->sales->whereBetween('closing_date', ["{$year}-04-01", "{$year}-06-31"]);
			if ($sales->isNotEmpty() && !collect($ignored_agents)->contains($user->agent_name)) {
				$quarter2Ten[] = [
					'agent' => $user->agent_name,
					'total' => collect($sales->all())->sum('pivot.sale_credit'),
				];
			}
		endforeach;

		//Get quarter 3
		foreach ($users as $user):
			$sales = $user->sales->whereBetween('closing_date', ["{$year}-07-01", "{$year}-09-31"]);
			if ($sales->isNotEmpty() && !collect($ignored_agents)->contains($user->agent_name)) {
				$quarter3Ten[] = [
					'agent' => $user->agent_name,
					'total' => collect($sales->all())->sum('pivot.sale_credit'),
				];
			}
		endforeach;

		//Get quarter 4
		foreach ($users as $user):
			$sales = $user->sales->whereBetween('closing_date', ["{$year}-10-01", "{$year}-12-31"]);
			if ($sales->isNotEmpty() && !collect($ignored_agents)->contains($user->agent_name)) {
				$quarter4Ten[] = [
					'agent' => $user->agent_name,
					'total' => collect($sales->all())->sum('pivot.sale_credit'),
				];
			}
		endforeach;
		foreach ($users as $user):
			$sales = $user->sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);
			if ($sales->isNotEmpty() && !collect($ignored_agents)->contains($user->agent_name)) {
				$ytd_sales[] = [
					'agent' => $user->agent_name,
					'total' => collect($sales->all())->sum('pivot.sale_credit'),
				];
			}
		endforeach;

		$quarter1Ten = collect($quarter1Ten)->sortByDesc('total');
		$quarter2Ten = collect($quarter2Ten)->sortByDesc('total');
		$quarter3Ten = collect($quarter3Ten)->sortByDesc('total');
		$quarter4Ten = collect($quarter4Ten)->sortByDesc('total');
		$ytd_sales = collect($ytd_sales)->sortByDesc('total');

		return response()->json([
			'quarter1Ten' => $quarter1Ten,
			'quarter2Ten' => $quarter2Ten,
			'quarter3Ten' => $quarter3Ten,
			'quarter4Ten' => $quarter4Ten,
			'ytd_sales' => $ytd_sales,
			'req' => $request->user(),
		]);
	}

	// REPORT FOR A SPECIFIC USER
	public function getReport(Request $request) {
		$options = $request->input('options');
		$year = $request->input('production_year');
		$agent = DB::table('users')->where('agent_name', $options['agent_name'])->first();
		$user_id = $agent->id;

		$sales = DB::table('sales')
			->join('sale_user', 'sales.id', '=', 'sale_user.sale_id')
			->where('sale_user.user_id', $user_id)
			->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"])
			->select([
				'sales.id', 'type', 'client_name', 'address',
				'sale_price', 'total_commission', 'sale_user.commission',
				'sale_user.split', 'sale_user.split_sale',
			])
			->get();

		$totals = [
			'total_agent_commission' => $sales->sum('commission'),
			'total_commission' => $sales->sum('total_commission'),
			'total_sales' => $sales->sum('sale_price'),
		];

		$sales->transform(function ($obj) {
			return collect($obj)
				->transform(function ($item, $key) {
					$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
					if (in_array($key, ['sale_price', 'total_commission', 'commission'])):
						return $fmt->formatCurrency($item, 'USD');
					elseif ($key === 'split'):
						return $item * 100;
					else:
						return $item;
					endif;
				});
		});
		$team_sales = $sales->where('split_sale', 'Yes');
		$sales = $sales->where('split_sale', 'No');

		return response()->json(
			[
				'agent_sales' => $sales,
				'totals' => $totals,
				'agent' => $agent,
				'split_sales' => $team_sales,
			]
		);
	}

    /**** Get all sales for admin user to see ***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function getAllSales(Request $request) {
		$user = $request->user();
		$year = date('Y');

		$sales = Sale::all();
		$sales = $sales->whereBetween('closing_date', ["2020-01-01", "2020-12-31"]);

		return response()->json(['sales' => $sales, 'req' => $user]);
	}

	/****  Get sales by search term   ****/
	public function searchSales(Request $request) {
        $search_term = $request->input('search_term');
        $user = $request->user();
        $search_by = $request->input('search_by');

        if (!isset($search_by)):
            $sales = DB::table('sale_user')
                ->join('sales', 'sales.id', '=', 'sale_user.sale_id')
                ->select('sales.*', 'sale_user.*')
                ->where('sales.client_name', 'LIKE', "%{$search_term}%")
                ->get();

            return response()->json(['sales' => $sales, 'req' => $user]);
        else:
            $x = count($search_by);
            $sales = DB::table('sale_user')
                ->join('sales', 'sales.id', '=', 'sale_user.sale_id')
                ->select('sales.*', 'sale_user.*')
                ->get();

            //Remove duplicates
            $sales = $sales->unique('sale_id');

            if ($search_term[0] !== null):
                $agent = User::where('agent_name', $search_term[0])->first('id')->id;
                $sales = $sales->where('user_id', '=', $agent);
            endif;

            for ($i = 1; $i < $x; $i++):
                if ($search_term[$i] !== null):
                    // Get sales by search term
                    $sales = $sales->where($search_by[$i], $search_term[$i]);
                endif;
            endfor;

            //Get current year
            $year = date('Y');
            // CHeck to see if there is a date range
            $bdate = ($request->input('beginDate') !== null) ? $request->input('beginDate') : "2020-01-01";
            $edate = ($request->input('endDate') !== null) ? $request->input('endDate') : "2020-12-31";
            $sales = $sales->whereBetween('closing_date', [$bdate, $edate]);

            // Get totals for filtered sales


            return response()->json(['sales' => $sales, 'req' => $user]);
        endif;
	}

	/**** GET SPECIFIC SALE FOR ADMIN ****/
	public function getSale(Request $request) {
		$sale_id = $request->input('id');
		$sale = Sale::where('id', $sale_id)->first();
		$users = $sale->users()->where('agent_name', '!=', 'Blue Realty')->get();

		return response()->json(['sale' => $sale, 'users' => $users]);
	}

	/*  Add new sale -  admin user  */
	public function store(Request $request) {
		$sale = $request->except('agent');
		$agents = $request->only('agent');
		$types = DB::table('type_of_sale')->get('type');
		$type_arr = [];
		foreach ($types as $key => $value) {
			array_push($type_arr, $value->type);
		}
		$cities = DB::table('cities')->get('place_name');
		$cities_arr = [];
		foreach ($cities as $key => $value) {
			array_push($cities_arr, $value->place_name);
		}
		$user = $request->user();

		$validator = Validator::make($sale, [
			'type' => [
				'required',
				Rule::in($type_arr),
			],
			'client_name' => 'required|string|min:3|max:200',
			'address' => 'required|string|min:3|max:200',
			'city' => [
				'required',
				Rule::in($cities_arr),
			],
			'closing_date' => 'required|date',
			'sale_price' => 'required|numeric|min:1',
			'total_commission' => 'required|numeric|min:1',
			'transaction_fee' => 'required|numeric|min:1',
			'blue_profit' => 'required|numeric|min:1',
			'mortgage_choice' => 'required',
			'title_choice' => 'required',
		]);
		// Check if validation fails
		if ($validator->fails()) {
			$errors = $validator->errors();
			return response()
				->json(
					[
						'errors' => $errors->all(),
					],
					412
				);
		} else {

			if (count($agents) < 1) {
				return response()
					->json(['errors' => 'At least one agent must be added'], 412);
			}
			$agents = collect($agents['agent']);
		}

		// Store Sale
		$sale['user'] = str_replace(' ', '_', $user->agent_name);
		$validated_sale = new Sale($sale);
		$validated_sale->save();

		// Attach sale_users
		foreach ($agents as $agent) {
			$agent_name = DB::table('users')->where('agent_name', $agent['name'])->get('id');

			$validator_agent = Validator::make($agent, [
				'name' => 'required',
				'commission' => 'required|numeric|min:1',
				'split' => 'required',
				'percent_of_sale' => 'required',
				'split_sale' => 'required',
			]);
			if ($validator_agent->fails()) {
				$validated_sale->delete();
				$err = $validator_agent->errors();
				return response()->json([
					'errors' => $err->all(),
				], 412);
			}
			if ($agent['percent_of_sale'] > 1) {
				$percent_of_sale = floatval($agent['percent_of_sale']) / 100;
			} else {
				$percent_of_sale = floatval($agent['percent_of_sale']);
			}
			$agent_sale = [
				'user_id' => $agent_name[0]->id,
				'sale_id' => $validated_sale->id,
				'split' => $agent['split'] / 100,
				'commission' => $agent['commission'],
				'percent_of_sale' => $percent_of_sale,
				'sale_credit' => $validated_sale->sale_price * $percent_of_sale,
				'blue_credit' => $validated_sale->blue_profit * $percent_of_sale,
				'transaction_credit' => $validated_sale->transaction_fee * $percent_of_sale,
				'split_sale' => $agent['split_sale'],
				'created_at' => Date::now('EST'),
				'updated_at' => Date::now('EST'),
			];
			$validated_sale->users()->attach($validated_sale->id, $agent_sale);
		}
		return response()->json(['data' => 'success'], 201);
	}

	/**** Update sale on admin side ****/
	public function updateRecord(Request $request) {
		$sale = $request->input('sale');
		$agents = $request->input('agent');
		$types = DB::table('type_of_sale')->get('type');
		$type_arr = [];
		foreach ($types as $key => $value) {
			array_push($type_arr, $value->type);
		}
		$validator_sale = Validator::make($sale, [
			'type' => [
				'required',
				Rule::in($type_arr),
			],
			'client_name' => 'required|string|min:3|max:200',
			'address' => 'required|string|min:3|max:200',
			'city' => 'required',
			'closing_date' => 'required|date',
			'sale_price' => 'required|numeric|min:1',
			'total_commission' => 'required|numeric|min:1',
			'transaction_fee' => 'required|numeric|min:1',
			'blue_profit' => 'required|numeric|min:1',
			'mortgage_choice' => 'required',
			'title_choice' => 'required',
		]);
		if ($validator_sale->fails()) {
			return response()->json(['errors' => $validator_sale->errors()->all()], 412);
		};

		$orig = (array) DB::table('sales')->where('id', $sale['id'])->first();
		$new_data = collect($sale)->diffAssoc($orig);
		if ($new_data->count() > 0):
			DB::table('sales')
				->where('id', $sale['id'])
				->update($new_data->toArray());
		endif;

		// Attach sale_users
		foreach ($agents as $agent) {
			$validator_agent = Validator::make($agent['pivot'], [
				'commission' => 'required|numeric|min:1',
				'split' => 'required',
				'percent_of_sale' => 'required|numeric',
			]);
			if ($validator_agent->fails()) {
				//TODO: Disregard changes if failed
				$err = $validator_agent->errors();
				return response()->json([
					'errors' => $err->all(),
				], 412);
			}
			// Check to see that split is entered as decimal
			if ($agent['pivot']['split'] > 1) {
				$split = floatval($agent['pivot']['split'] / 100);
			} else {
				$split = floatval($agent['pivot']['split']);
			}
			if (!$new_data->has('sale_price')) {
				$agent_sale = [
					'split' => $split,
					'commission' => $agent['pivot']['commission'],
					'percent_of_sale' => $agent['pivot']['percent_of_sale'],
					'sale_credit' => $agent['pivot']['percent_of_sale'] * $sale['sale_price'],
					'updated_at' => Date::now('EST'),
				];
			} else {
				$agent_sale = [
					'split' => $split,
					'commission' => $agent['pivot']['commission'],
					'percent_of_sale' => $agent['pivot']['percent_of_sale'],
					'sale_credit' => $agent['pivot']['percent_of_sale'] * $new_data['sale_price'],
					'updated_at' => Date::now('EST'),
				];
			}

			Sale::find($sale['id'])->users()->updateExistingPivot($agent['id'], $agent_sale);
			//            $sale_validated->users()->updateExistingPivot($agent['id'], $agent_sale);
		}

		return response()->json(['success' => "Success"], 201);
	}

	public function deleteSaleUser(Request $request) {
		$sale = Sale::find($request->input('sale_id'));

		$sale->users()->detach($request->input('user_id'));

		return response()->json(['msg' => 'success']);
	}

	/**** Attach commission row to sale  */
	public function addCommission(Request $request) {
		$sale = Sale::find($request->input('sale_id'));
		$inputs = $request->input('sale_info');
		$inputs['split'] = ($inputs['split'] > 1) ? $inputs['split'] / 100 : floatval($inputs['split']);

		$validator = Validator::make($inputs, [
			'commission' => 'required|numeric|min:1',
			'split' => 'required|numeric',
			'percent_of_sale' => 'required|numeric|max:1',
		]);
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()->all()], 412);
		}
		$arr = array_merge($inputs, [
			'sale_credit' => $sale->sale_price * $inputs['percent_of_sale'],
			'blue_credit' => $sale->blue_profit * $inputs['percent_of_sale'],
			'transaction_credit' => $sale->transaction_fee * $inputs['percent_of_sale'],
			'created_at' => Date::now('EST'),
			'updated_at' => Date::now('EST'),
		]);

		// Get user id for attach
		$user = User::where('agent_name', $inputs['agent_name'])->first();

		// Remove agent_name before attaching, it's not a column in sale_user table
		unset($arr['agent_name']);
		$sale->users()->attach($user->id, $arr);

		return response()->json(['msg' => 'success']);
	}

	// PROFIT PER AGENT
	public function profit(Request $request) {
		$user = $request->user();
		$year = $request->input('production_year');

		$ignored_agents = DB::table('ignored_agents')->get('agent_name');
		$ignored_agents->transform(function ($item) {
			return $item->agent_name;
		});
		$users = User::all()->whereNotIn('agent_name', $ignored_agents);

		foreach ($users as $user) {
			$sales = $user->sales;
			$sales = $sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);
			$profits[] = [
				'agent_name' => $user->agent_name,
				'buyers' => $sales
					->where('type', 'Buyer')
					->where('pivot.split', '>=', 0.5)->count(),
				'sellers' => $sales->where('type', 'Seller')
					->where('pivot.split', '>=', 0.5)->count(),
				'rentals' => $sales->where('type', 'Rental')
					->where('pivot.split', '>=', 0.5)->count(),
				'referrals' => $sales->where('type', 'Referral')
					->where('pivot.split', '>=', 0.5)->count(),
				'units_sold' => $sales->whereIn('type', ['Seller', 'Buyer', 'Rental', 'Referral'])
					->where('pivot.split', '>=', 0.5)->count(),
				'blue_income' => $sales->sum('pivot.blue_credit'),
				'transaction_fees' => $sales->sum('pivot.transaction_credit'),
				'agent_income' => $sales->sum('pivot.commission'),
				'total_income' => $sales->sum('pivot.blue_credit') + $sales->sum('pivot.transaction_credit'),

			];
		}
		return response()->json(['profits' => $profits, 'req' => $user]);
	}

	/*** Get all agents for Agent Control */
	public function getAgents() {
		$agents = User::all();
		$ignored_agents = DB::table('ignored_agents')->get('agent_name');
		$ignored_agents->transform(function ($item) {
			return $item->agent_name;
		});

		$agents->each(function ($item) {
			$item->current_split = floatval($item->current_split) * 100;
			$item->membership_fee = number_format(
				floatval($item->membership_fee),
				2
			);
		});
		$agents = $agents->whereNotIn('agent_name', $ignored_agents->toArray());

		return response()->json(['agents' => $agents]);
	}

	/*** Update an agent's info on Agent Control Tab
		     * @param Request $request
		     * @return \Illuminate\Http\JsonResponse
	*/
	public function updateAgent(Request $request) {
		$agent = $request->input('agent');
		$orig_agent = User::find($agent['id']);
		$employee_titles = DB::table('employee_titles')->get('title');
		$employee_titles->transform(function ($item) {
			return $item->title;
		});

		$data = [];
		$rules = [];

		if ($agent['agent_name'] !== $orig_agent->agent_name):
			$data['agent_name'] = $agent['agent_name'];
			$rules['agent_name'] = 'string|max:255|unique:users';
		endif;
		if ($agent['title'] !== $orig_agent->title):
			$data['title'] = $agent['title'];
			$rules['title'] = ['string', 'max:255', Rule::in($employee_titles)];
		endif;
		if ($agent['email'] !== $orig_agent->email):
			$data['email'] = $agent['email'];
			$rules['email'] = 'string|email|max:255|unique:users';
		endif;
		if (
			$agent['phone'] !== '5555555555' and
			$agent['phone'] !== '' and
			$agent['phone'] !== $orig_agent->phone
		):

			if (strlen($agent['phone']) > 10):
				$data['phone'] = str_replace('-', '', $agent['phone']);
				$rules['phone'] = 'string|size:10';
			elseif (strlen($agent['phone']) === 10):
				$data['phone'] = $agent['phone'];
				$rules['phone'] = 'string|size:10';
			endif;
		endif;
		if (floatval($agent['current_split']) !== $orig_agent->current_split):
			$data['current_split'] =
			($agent['current_split'] <= 1) ? $agent['current_split'] :
			floatval($agent['current_split'] / 100);
			$rules['current_split'] = 'max:1|numeric';
		endif;
		if (floatval($agent['membership_fee']) !== $orig_agent->membership_fee):
			$data['membership_fee'] = floatval($agent['membership_fee']);
			$rules['membership_fee'] = 'numeric';
		endif;

		$messages = [
			'phone.size' => 'Phone format - 555-555-5555',
			'email.unique' => 'That email is already in use',
			'title.in' => 'Please select a title from the dropdown',
		];
		$validator = Validator::make($data, $rules, $messages);
		if ($validator->fails()):
			return response()->json(['errors' => $validator->errors()->all()], 412);
		endif;
		// Make final adjustments to data before
		// entered into the DB
		if ($agent['phone'] !== null and $agent['phone'] !== ''):
			$data['phone'] = substr($agent['phone'], 0, 3) . '-' .
			substr($agent['phone'], 3, 3) . '-' .
			substr($agent['phone'], 6);
		else:
			$data['phone'] = $agent['phone'];
		endif;
		if (
			$agent['dob'] !== $orig_agent->dob and
			$agent['dob'] !== null and
			$agent['dob'] !== '' and
			$agent['dob'] !== 'N/A'
		):
			$agent['dob'] = str_replace('-', '/', $agent['dob']);
			$data['dob'] = \date('m-d', strtotime($agent['dob']));
		else:
			$data['dob'] = $agent['dob'];
		endif;

		try {
			DB::table('users')
				->where('id', $agent['id'])
				->update($data);
		} catch (\Exception $exception) {
			return response()->json(['msg' => 'There was an error']);
		}
		return response()->json(['msg' => 'Successfully Updated!']);
	}

	public function membershipDetails() {
		$details = DB::table('membership_dues')
			->get();

		$details->each(function ($item) {
			$tmp = 0;
			$num = date('m');
			$arr = (array) $item;
			$month = array_splice($arr, 1, $num);
			foreach ($month as $key => $value) {
				$tmp = ($value !== 1) ? $tmp + 1 : $tmp + 0;
			}
			$fee = DB::table('users')
				->where('agent_name', $item->agent_name)
				->first('membership_fee');

			$item->bal = number_format(floatval($fee->membership_fee * $tmp), 2);
		});

		return response()->json(['agents' => $details]);
	}

	public function membershipUpdate(Request $request) {
		$agent_membership = $request->input('agent_membership');
		foreach ($agent_membership as $key => $value) {
			if ($value === 'true') {
				$agent_membership[$key] = '1';
			} elseif ($value === 'false') {
				$agent_membership[$key] = '0';
			}
		}

		DB::table('membership_dues')
			->where('agent_name', $agent_membership['agent_name'])
			->update($agent_membership);

		return response()->json(['msg' => 'Successfully Saved']);
	}
}
