<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Sale;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;
use stdClass;

class PDFController extends Controller
{
    public function generatePDF($agent_name, $production_year)
    {
        $user = User::where('agent_name', $agent_name)->first();
        $sales = $user->sales->whereBetween('closing_date', ["{$production_year}-01-01", "{$production_year}-12-31"]);
        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $totals = [
            'sale_price' => $fmt->formatCurrency($sales->sum('sale_price'), "USD"),
            'total_commission' => $fmt->formatCurrency($sales->sum('total_commission'), "USD"),
            'agent_commission' => $fmt->formatCurrency($sales->sum('pivot.commission'), "USD")
        ];

        $sales->each(function ($sale, $key) {
            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
            $sale->toArray();
            $sale['sale_price'] = $fmt->formatCurrency($sale['sale_price'], "USD");
            $sale['total_commission'] = $fmt->formatCurrency($sale['total_commission'], "USD");
            $sale['pivot']['commission'] = $fmt->formatCurrency($sale['pivot']['commission'], "USD");
        });

        $pdf = PDF::loadView(
            'pdfGen',
            [
                'agent' => $agent_name,
                'sales' => $sales,
                'year' => $production_year,
                'totals' => $totals
            ]
        );

        return $pdf->stream();
    }

    /*** Generate PDF for Detail sale view */
    public function generateSingleSalePDF($sale_id)
    {
        $sale =
            collect(DB::table('sales')
                ->where('id', $sale_id)
                ->first())
            ->except(
                ['created_at', 'updated_at', 'user']
            );
        $agents = DB::table('sale_user')->where('sale_id', $sale_id)->get(['user_id', 'commission', 'split']);
        $commission_breakdown = [];

        foreach ($agents as $key => $value) {
            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
            $name =
                User::where('id', $value->user_id)->first('agent_name');
            $temp_arr = [];
            $temp_arr['Name'] = $name->agent_name;
            $temp_arr['Commission'] = $fmt->formatCurrency($value->commission, "USD");
            $temp_arr['Split'] = $value->split * 100 . "%";

            array_push($commission_breakdown, $temp_arr);
        }

        $formatted_sale = $sale->map(function ($item, $key) {
            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
            if (is_numeric($item) && $key !== 'id') :
                $item = $fmt->formatCurrency($item, "USD");
                return $item;
            else :
                return $item;
            endif;
        })->toArray();

        $pdf = PDF::loadView('detail_pdf', ['sale' => $formatted_sale, 'commission_breakdown' => $commission_breakdown]);

        return $pdf->stream();

        // return view('detail_pdf', ['sale' => $sale]);
    }
}
