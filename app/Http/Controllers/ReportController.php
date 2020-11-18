<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class ReportController extends Controller
{
    // GET ALL USERS ON SPECIFIC SALE
    public function getSaleUser(Request $request)
    {
        $sale_id = $request->input('id');

        $sale_users = DB::table('sale_user')
            ->where('sale_id', '=', $sale_id)
            ->get();
        $sale_users->each(function ($value) {
            $value->name = DB::table('users')->where('id', $value->user_id)->get('agent_name');
            $value->name = $value->name[0]->agent_name;
        });

        return response()->json(['commission' => $sale_users]);
    }
}
