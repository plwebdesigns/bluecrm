<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ScriptController extends Controller {

    public function runScript()
    {
        /* Script for processing sales csv  */

//        $lines = file('blue_q4_3.csv');
//        $index = 500;
//
//        foreach($lines as $line){
//
//            $new_line = str_getcsv($line, ",");
//
//
//            if ($new_line[0] != ''):
//                $sale = new Sale;
//                $index += 1;
//                //Build sale object
//                $sale->id = $index;
//                $sale->type = $new_line[0];
//                $sale->agent_name = $new_line[1];
//                $sale->client_name = $new_line[2];
//                $sale->sale_price = floatval(str_replace(",", '', trim($new_line[3], '$')));
//                $sale->address = $new_line[4];
//                $sale->city = $new_line[5];
//                $sale->closing_date = date('Y-m-d', strtotime($new_line[6]));
//                $sale->total_commission = floatval(str_replace(',', '', trim($new_line[7], '$')));
//                $sale->blue_profit = floatval(str_replace(',', '', trim($new_line[12], '$')));
//                $sale->transaction_fee = floatval(str_replace(',', '', trim($new_line[13], '$')));
//                $sale->title_choice = $new_line[14];
//                $sale->mortgage_choice = $new_line[15];
//                $sale->notes = $new_line[16];
//
//                $sale->save();
//            endif;
//            if ($user = User::where('agent_name', trim($new_line[8]))->get()->toArray()):
//                $user = User::where('agent_name', trim($new_line[8]))->get()->toArray();
//
//                $percent_of_sale = floatval(str_replace('%', '', $new_line[9]));
//
//                $agent_sale = [
//                    'user_id' => trim($user[0]['id']),
//                    'sale_id' => $index,
//                    'split' => floatval(str_replace('%', '', $new_line[10])),
//                    'commission' => floatval(str_replace(',', '', trim($new_line[11], '$'))),
//                    'percent_of_sale' => $percent_of_sale,
//                    'sale_credit' => $sale->sale_price * $percent_of_sale,
//                    'blue_credit' => $sale->blue_profit * floatval(str_replace('%', '', $new_line[9])),
//                    'transaction_credit' => $sale->transaction_fee * floatval(str_replace('%', '', $new_line[9]))
//                ];
//
//                $sale->users()->attach("{$user[0]['id']}{$sale->id}", $agent_sale);
//            endif;
//
//        }

        /*      Script for inserting agent info     */

//        $users = User::all();
//
//        foreach ($users as $user):
//            if ($user->api_token == null):
//                $user->api_token = Str::random(60);
//                $user->save();
//            endif;
//        endforeach;
    }
}
