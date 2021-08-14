<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use NumberFormatter;

class AdminController extends Controller {
	/*
	*
	* This function will get the quarter breakdown
	* and the YTD for the current year
	*
	*/
	public function quarterBreakDown(Request $request) {
		$user = $request->user();
		$year = $request->input('production_year');
		if ($user->isAdmin && Gate::allows('create-sale', Sale::class)):

			// Intialize array of variables
			$sales_by_quarter[] = [
				'volume' => 0,
				'units' => 0,
				'sellers' => 0,
				'buyers' => 0,
				'rentals' => 0,
				'referrals' => 0,
				'trans_fees' => 0,
				'blue_profit' => 0,
				'membership_dues' => 0,
				'total_profit' => 0
			];

			$all_sales = Sale::all();
			$all_sales = $all_sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);
			$split_sales = [
				0 => $all_sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-03-31"]),
				1 => $all_sales->whereBetween('closing_date', ["{$year}-04-01", "{$year}-06-31"]),
				2 => $all_sales->whereBetween('closing_date', ["{$year}-07-01", "{$year}-09-31"]),
				3 => $all_sales->whereBetween('closing_date', ["{$year}-10-01", "{$year}-12-31"])
			];

			foreach ($split_sales as $no => $quarter) {
				$sales_by_quarter[$no] = [
					'volume' => $quarter->sum('sale_price'),
					'units' => $quarter->count(),
					'sellers' => $quarter->where('type', 'Seller')->count(),
					'buyers' => $quarter->where('type', 'Buyer')->count(),
					'rentals' => $quarter->where('type', 'Rental')->count(),
					'referrals' => $quarter->where('type', 'Referral')->count(),
					'trans_fees' => $quarter->sum('transaction_fee'),
					'blue_profit' => $quarter->sum('blue_profit'),
					'membership_dues' => $quarter->sum('membership_dues_paid'),
					'total_profit' => 0
				];
				$sales_by_quarter[$no]['total_profit'] = $sales_by_quarter[$no]['trans_fees'] +
				$sales_by_quarter[$no]['blue_profit'] +
				$sales_by_quarter[$no]['membership_dues'];
			}
			// Get YTD numbers
			$sales_by_quarter[4] = [
				'volume' => $all_sales->sum('sale_price'),
				'units' => $all_sales->count(),
				'sellers' => $all_sales->where('type', 'Seller')->count(),
				'buyers' => $all_sales->where('type', 'Buyer')->count(),
				'rentals' => $all_sales->where('type', 'Rental')->count(),
				'referrals' => $all_sales->where('type', 'Referral')->count(),
				'trans_fees' => $all_sales->sum('transaction_fee'),
				'blue_profit' => $all_sales->sum('blue_profit'),
				'membership_dues' => $all_sales->sum('membership_dues_paid'),
				'total_profit' => 0
			];
			$sales_by_quarter[4]['total_profit'] = $sales_by_quarter[4]['trans_fees'] +
			$sales_by_quarter[4]['blue_profit'] +
			$sales_by_quarter[4]['membership_dues'];

			$summary = [
				$sales_by_quarter[0],
				$sales_by_quarter[1],
				$sales_by_quarter[2],
				$sales_by_quarter[3],
				$sales_by_quarter[4]
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
		$ytd_sales = [];

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

		// Calc totals
		$q1Total = $quarter1Ten->sum('total');
		$q2Total = $quarter2Ten->sum('total');
		$q3Total = $quarter3Ten->sum('total');
		$q4Total = $quarter4Ten->sum('total');
		$ytdTotal = $ytd_sales->sum('total');

		return response()->json([
			'quarter1Ten' => $quarter1Ten,
			'quarter2Ten' => $quarter2Ten,
			'quarter3Ten' => $quarter3Ten,
			'quarter4Ten' => $quarter4Ten,
			'ytd_sales' => $ytd_sales,
			'req' => $request->user(),
			'q1Total' => $q1Total,
			'q2Total' => $q2Total,
			'q3Total' => $q3Total,
			'q4Total' => $q4Total,
			'ytdTotal' => $ytdTotal
		]);
	}

	// REPORT FOR A SPECIFIC USER
	public function getReport(Request $request): JsonResponse
    {
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
				'sale_user.split', 'sale_user.split_sale', 'sale_user.percent_of_sale'
			])
			->get();
        $team_sales = $sales->where('percent_of_sale', '<', 1);
        $sales = $sales->where('percent_of_sale', '=', 1); //Remove team sales

        //Calculate totals for currency fields
        $totals = [
            'total_agent_commission' => $sales->sum('commission'),
            'total_commission' => $sales->sum('total_commission'),
            'total_sales' => $sales->sum('sale_price'),
        ];

        $totals_team_sales = [
            'total_agent_commission' => $team_sales->sum('commission'),
            'total_commission' => $team_sales->sum('total_commission'),
            'total_sales' => $team_sales->sum('sale_price')
        ];

		//Format currency fields
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

		return response()->json(
			[
				'agent_sales' => $sales,
				'totals' => $totals,
				'agent' => $agent,
				'split_sales' => $team_sales,
                'total_team_sales' => $totals_team_sales
			]
		);
	}

    /**** Get all sales for admin user to see ***
     * @param Request $request
     * @return JsonResponse
     */
	public function getAllSales(Request $request) {
		$user = $request->user();
		$year = date('Y');

		$sales = Sale::all();
		$sales = $sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);

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
        elseif ($search_by === 'address'):
            $sales = DB::table('sale_user')
                ->join('sales', 'sales.id', '=', 'sale_user.sale_id')
                ->select('sales.*', 'sale_user.*')
                ->where('sales.address', 'LIKE', "%{$search_term}%")
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
            $bdate = ($request->input('beginDate') !== null) ? $request->input('beginDate') : "{$year}-01-01";
            $edate = ($request->input('endDate') !== null) ? $request->input('endDate') : "{$year}-12-31";
            $sales = $sales->whereBetween('closing_date', [$bdate, $edate]);

            // Get totals for filtered sales
			$total_sale_price = $sales->sum('sale_price');
			$total_commission = $sales->sum('total_commission');

            return response()->json(
				[
					'sales' => $sales,
					'req' => $user,
					'total_sale_price' => $total_sale_price,
					'total_commission' => $total_commission
				]
			);
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
			'sale_price' => 'required|numeric',
			'total_commission' => 'required|numeric',
			'transaction_fee' => 'required|numeric',
			'blue_profit' => 'required|numeric',
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
				'commission' => 'required|numeric',
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

    /**** Update sale on admin side ***
     * @param Request $request
     * @return JsonResponse
     */
	public function updateRecord(Request $request): JsonResponse
    {
		$sale = $request->input('sale');
		$agents = $request->input('agent');
		$type_arr = DB::table('type_of_sale')->pluck('type')->toArray();

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

		$orig = Sale::findOrFail($sale['id']);
		$new_data = collect($sale)->diffAssoc($orig);
    
		if ($new_data->count() > 0):
			foreach ($new_data as $key => $value) {
				$orig->$key = $value;
			}
			$orig->save();
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
	public function profit(Request $request): JsonResponse
    {
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

			//Initialize Variables
			$buyers = 0;
			$sellers = 0;
			$rentals = 0;
			$referrals = 0;
			//Get count of each type of sale using percent of sale
			foreach ($sales as $sale) {
				if ($sale['type'] === 'Buyer') {
					$buyers += $sale['pivot']['percent_of_sale'];
				}
				elseif($sale['type'] === 'Seller') {
					$sellers += $sale['pivot']['percent_of_sale'];
				}
				elseif($sale['type'] === 'Rental') {
					$rentals += $sale['pivot']['percent_of_sale'];
				}
				else {
					$referrals += $sale['pivot']['percent_of_sale'];
				}
			}
			$units_sold = $buyers + $sellers + $rentals + $referrals;
			$trans_credit = $sales->sum('pivot.transaction_credit');
			$blue_credit = $sales->sum('pivot.blue_credit');
			$membership_dues = $sales->sum('pivot.membership_dues_paid');

			$profits[$user['agent_name']] = [
			    'agent_name' => $user['agent_name'],
                'buyers' => $buyers,
                'sellers' => $sellers,
                'rentals' => $rentals,
                'referrals' => $referrals,
                'units_sold' => $units_sold,
                'transaction_fees' => $trans_credit,
                'membership_dues' => $membership_dues,
				'blue_income' => $blue_credit,
                'agent_income' => $sales->sum('pivot.commission'),
                'total_income' => $blue_credit + $trans_credit + $membership_dues,
                'total_sales' => $sales->sum('pivot.sale_credit')
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

    /***
     * @param Request $request
     * @return JsonResponse
     *
     *
     * Allows admin user to change password for any agent
     */
    public function updateUserPassword(Request $request): JsonResponse
    {
        if ($request->user()->isAdmin) {
            $data = $request->input();
            $user = User::where('email', $data['email'])->first();

            $validator = Validator::make($data, [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->toArray();
                return response()->json(['errors' => $errors]);
            }

            $user->password = Hash::make($data['password']);
            try {
                $user->save();
            }
            catch (\Exception $exception) {
                return response()->json(['errors' => ['SaveError' => [0 => $exception->getMessage()]]]);
            }

            $data['message'] = 'Successfully changed';
            return response()->json(['message' => $data['message']]);
        }
    }

	/*** Update an agent's info on Agent Control Tab
		     * @param Request $request
		     * @return JsonResponse
	*/
	public function updateAgent(Request $request) {
		$agent = $request->input('agent');
		$orig_agent = User::find($agent['id']);

		if (isset($agent['current_split'])) {
			$split = $agent['current_split'];
			if ($split > 100 || $split < 0) {
				return response()->json(['err' => 'Split should be 0-100']);
			}
			$split = ($split > 1) ? $split/100 : $split;
		}
		if (isset($agent['membership_fee'])) {
			try {
				$agent['membership_fee'] = floatval($agent['membership_fee']);
			} catch (\Exception $e) {
				return response()->json(['err' => 'Could not parse membership']);
			}
		}

		$diff = collect($agent)->diff($orig_agent);
		if (empty($diff)) {
			return response()->json(['msg' => 'No changes were made']);
		}
		foreach ($diff as $key => $value) {
			$orig_agent->$key = $value;
		}
		$orig_agent->save();

		return response()->json(['msg' => 'Successfully Saved!']);
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
