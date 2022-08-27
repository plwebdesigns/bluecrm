<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function index(Request $request)
    {

        return view('layouts.basic');
    }

    /**** DASHBOARD DATA ****/
    public function dash(Request $request)
    {
        $users = User::has('sales')->get();
        // Get ignored agents from DB
        $ignored_agents = DB::table('ignored_agents')->pluck('agent_name')->toArray();
        $users = $users->whereNotIn('title', 'Inactive')->whereNotIn('agent_name', $ignored_agents);
        $quarter1Ten = [];
        $quarter2Ten = [];
        $quarter3Ten = [];
        $quarter4Ten = [];
        $ytd_sales = [];
	    $year = $request->input('production_year') ?: date('Y');

        //Get quarter 1
        foreach ($users as $user) :
            $sales = $user->sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-03-31"]);
            if ($sales->isNotEmpty()) {
                $quarter1Ten[] = [
                    'agent' => $user->agent_name,
                    'total' => collect($sales->all())->sum('pivot.sale_credit'),
                ];
            }
        endforeach;
        //Get quarter 2
        foreach ($users as $user) :
            $sales = $user->sales->whereBetween('closing_date', ["{$year}-04-01", "{$year}-06-31"]);
            if ($sales->isNotEmpty()) {
                $quarter2Ten[] = [
                    'agent' => $user->agent_name,
                    'total' => collect($sales->all())->sum('pivot.sale_credit'),
                ];
            }
        endforeach;

        //Get quarter 3
        foreach ($users as $user) :
            $sales = $user->sales->whereBetween('closing_date', ["{$year}-07-01", "{$year}-09-31"]);
            if ($sales->isNotEmpty()) {
                $quarter3Ten[] = [
                    'agent' => $user->agent_name,
                    'total' => collect($sales->all())->sum('pivot.sale_credit'),
                ];
            }
        endforeach;

        //Get quarter 4
        foreach ($users as $user) :
            $sales = $user->sales->whereBetween('closing_date', ["{$year}-10-01", "{$year}-12-31"]);
            if ($sales->isNotEmpty()) {
                $quarter4Ten[] = [
                    'agent' => $user->agent_name,
                    'total' => collect($sales->all())->sum('pivot.sale_credit'),
                ];
            }
        endforeach;
        foreach ($users as $user) :
            $sales = $user->sales;
            $sales = $sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);
            if ($sales->isNotEmpty()) {
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

    /****  Get sales for a specific Agent on my production page  ****/
    public function getSales(Request $request)
    {
        $user = $request->user();
        $sales = $user->sales;
        $year = $request->input('production_year');

        $split_sales = $sales
            ->where('pivot.percent_of_sale', '<', 1)
            ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);
        $sales = $sales
            ->where('pivot.percent_of_sale', '=', 1)
            ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);

        return response()->json([
            'sales' => $sales,
            'team_sales' => $split_sales,
            'ytd_commission' => $sales->sum('pivot.commission'),
            'ytd_sales' => $sales->sum('pivot.sale_credit'),
            'ytd_split_commission' => $split_sales->sum('pivot.commission'),
            'ytd_split_sales' => $split_sales->sum('sale_price'),
            'req' => $user,
        ]);
    }

    /**** Get quarter breakdown for single agent ****/
    public function quarterBreakdown(Request $request)
    {
        $user = $request->user();
        $sales = DB::table('sales')
            ->join('sale_user', 'sales.id', '=', 'sale_user.sale_id')
            ->where('sale_user.user_id', $user->id)
            ->select('sales.type', 'sales.closing_date', 'sale_user.*')
            ->get();

        $year = $request->input('production_year');
        $q1 = $sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-03-31"]);
        $q2 = $sales->whereBetween('closing_date', ["{$year}-04-01", "{$year}-06-31"]);
        $q3 = $sales->whereBetween('closing_date', ["{$year}-07-01", "{$year}-09-31"]);
        $q4 = $sales->whereBetween('closing_date', ["{$year}-10-01", "{$year}-12-31"]);
        $ytd = $sales->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"]);
        $breakdown = [
            'Quarter 1' => [
                'volume' => $q1->sum('sale_credit'),
                'units' => $q1->count(),
                'buyers' => $q1->where('type', 'Buyer')->count(),
                'sellers' => $q1->where('type', 'Seller')->count(),
                'rentals' => $q1->where('type', 'Rental')->count(),
                'referrals' => $q1->where('type', 'Refferal')->count(),
                'trans' => $q1->sum('transaction_credit')
            ],
            'Quarter 2' => [
                'volume' => $q2->sum('sale_credit'),
                'units' => $q2->count(),
                'buyers' => $q2->where('type', 'Buyer')->count(),
                'sellers' => $q2->where('type', 'Seller')->count(),
                'rentals' => $q2->where('type', 'Rental')->count(),
                'referrals' => $q2->where('type', 'Refferal')->count(),
                'trans' => $q2->sum('transaction_credit')
            ],
            'Quarter 3' => [
                'volume' => $q3->sum('sale_credit'),
                'units' => $q3->count(),
                'buyers' => $q3->where('type', 'Buyer')->count(),
                'sellers' => $q3->where('type', 'Seller')->count(),
                'rentals' => $q3->where('type', 'Rental')->count(),
                'referrals' => $q3->where('type', 'Refferal')->count(),
                'trans' => $q3->sum('transaction_credit')
            ],
            'Quarter 4' => [
                'volume' => $q4->sum('sale_credit'),
                'units' => $q4->count(),
                'buyers' => $q4->where('type', 'Buyer')->count(),
                'sellers' => $q4->where('type', 'Seller')->count(),
                'rentals' => $q4->where('type', 'Rental')->count(),
                'referrals' => $q4->where('type', 'Refferal')->count(),
                'trans' => $q4->sum('transaction_credit')
            ],
            'YTD' => [
                'volume' => $ytd->sum('sale_credit'),
                'units' => $ytd->count(),
                'buyers' => $ytd->where('type', 'Buyer')->count(),
                'sellers' => $ytd->where('type', 'Seller')->count(),
                'rentals' => $ytd->where('type', 'Rental')->count(),
                'referrals' => $ytd->where('type', 'Referral')->count(),
                'trans' => $ytd->sum('transaction_credit')
            ]
        ];

        return response()->json(['breakdown' => $breakdown]);
    }

    /****  Get sales by search term on my all sales tab  ****/
    public function searchSales(Request $request)
    {

        $search_term = $request->input('search_term');
        $user = $request->user();

        $sales = DB::table('sales')
            ->where('agent_name', "LIKE", "%{$search_term}%")
            ->orWhere(
                'client_name',
                'LIKE',
                "%{$search_term}%"
            )
            ->get();

        return response()->json(['sales' => $sales, 'req' => $user]);
    }

    /*** Get sales by search on my production page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProduction(Request $request)
    {
        $search_term = $request->input('term');
        $year = $request->input('production_year');
        $user = $request->user();

        if ($search_term === '' or $search_term === null) :
            $sales = $user->sales()
                ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"])
                ->where('split_sale', 'No')
                ->get();
            $split_sales = $user->sales()
                ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"])
                ->where('split_sale', 'Yes')
                ->get();
        else :
            $sales = $user->sales()
                ->where('client_name', "LIKE", "%{$search_term}%")
                ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"])
                ->where('split_sale', 'No')
                ->get();
            $split_sales = $user->sales()
                ->where('client_name', "LIKE", "%{$search_term}%")
                ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"])
                ->where('split_sale', 'Yes')
                ->get();
        endif;


        return response()->json(['sales' => $sales, 'split_sales' => $split_sales]);
    }
}
