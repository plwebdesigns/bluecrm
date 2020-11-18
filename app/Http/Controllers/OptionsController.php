<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OptionsController extends Controller
{
    /*** Get all of the Options from tables */
    public function getAllLists(Request $request)
    {
        $ignored_agents = DB::table('ignored_agents')->get('agent_name');
        $agents_arr = [];
        foreach ($ignored_agents as $key => $value) {
            array_push($agents_arr, $value->agent_name);
        }
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
        $emp_titles = DB::table('employee_titles')->get('title');
        $emp_titles_arr = [];
        foreach ($emp_titles as $value) {
            array_push($emp_titles_arr, $value->title);
        }

        return response()->json([
            'ignored_agents' => $agents_arr,
            'types_of_sales' => $type_arr,
            'cities' => $cities_arr,
            'mortgages' => $mortgages_arr,
            'titles' => $titles_arr,
            'emp_titles' => $emp_titles_arr
        ]);
    }

    /* Update all options */
    public function updateOptions(Request $request)
    {
        //Get data from request
        $ignored_agents = $request->input('ignored_agents');
        $types = $request->input('types_of_sales');
        $cities = $request->input('cities');
        $mortgages = $request->input('mortgages');
        $titles = $request->input('titles');
        $emp_titles = $request->input('emp_titles');

        //Get original data from DB
        $orig_agents = DB::table('ignored_agents')->get('agent_name');
        $orig_agents_arr = [];
        foreach ($orig_agents as $key => $value) {
            array_push($orig_agents_arr, $value->agent_name);
        }
        $orig_cities = DB::table('cities')->get('place_name');
        $orig_cities_arr = [];
        foreach ($orig_cities as $key => $value) {
            array_push($orig_cities_arr, $value->place_name);
        }
        $orig_types = DB::table('type_of_sale')->get('type');
        $orig_types_arr = [];
        foreach ($orig_types as $key => $value) {
            array_push($orig_types_arr, $value->type);
        }
        $orig_mortgages = DB::table('mortgage_names')->get('mortgage_names');
        $orig_mortgages_arr = [];
        foreach ($orig_mortgages as $value) {
            array_push($orig_mortgages_arr, $value->mortgage_names);
        }
        $orig_titles = DB::table('title_names')->get('title_names');
        $orig_titles_arr = [];
        foreach ($orig_titles as $value) {
            array_push($orig_titles_arr, $value->title_names);
        }
        $orig_emp_titles = DB::table('employee_titles')->get('title');
        $orig_emp_titles_arr = [];
        foreach ($orig_emp_titles as $value) {
            array_push($orig_emp_titles_arr, $value->title);
        }

        //Get diff from originals and data sent via request
        $diff_agents = array_diff($orig_agents_arr, $ignored_agents);
        $new_agents = array_diff($ignored_agents, $orig_agents_arr);
        $diff_cities = array_diff($orig_cities_arr, $cities);
        $new_cities = array_diff($cities, $orig_cities_arr);
        $diff_types = array_diff($orig_types_arr, $types);
        $new_types = array_diff($types, $orig_types_arr);
        $diff_mortgages = array_diff($orig_mortgages_arr, $mortgages);
        $new_mortgages = array_diff($mortgages, $orig_mortgages_arr);
        $diff_titles = array_diff($orig_titles_arr, $titles);
        $new_titles = array_diff($titles, $orig_titles_arr);
        $diff_emp_titles = array_diff($orig_emp_titles_arr, $emp_titles);
        $new_emp_titles = array_diff($emp_titles, $orig_emp_titles_arr);

        // Insert or delete only if array is not empty
        if (count($diff_agents) > 0) {
            foreach ($diff_agents as  $value) {
                DB::table('ignored_agents')->where('agent_name', $value)->delete();
            }
        } elseif (count($new_agents) > 0) {
            foreach ($new_agents as $value) {
                DB::table('ignored_agents')->insert(['agent_name' => $value]);
            }
        }
        if (count($diff_cities) > 0) {
            foreach ($diff_cities as  $value) {
                DB::table('cities')->where('place_name', $value)->delete();
            }
        } elseif (count($new_cities) > 0) {
            foreach ($new_cities as $value) {
                DB::table('cities')->insert(['place_name' => $value]);
            }
        }
        if (count($diff_types) > 0) {
            foreach ($diff_types as  $value) {
                DB::table('type_of_sale')->where('type', $value)->delete();
            }
        } elseif (count($new_types) > 0) {
            foreach ($new_types as $value) {
                DB::table('type_of_sale')->insert(['type' => $value]);
            }
        }
        if (count($diff_mortgages) > 0) {
            foreach ($diff_mortgages as  $value) {
                DB::table('mortgage_names')->where('mortgage_names', $value)->delete();
            }
        } elseif (count($new_mortgages) > 0) {
            foreach ($new_mortgages as $value) {
                DB::table('mortgage_names')->insert(['mortgage_names' => $value]);
            }
        }
        if (count($diff_titles) > 0) {
            foreach ($diff_titles as  $value) {
                DB::table('title_names')->where('title_names', $value)->delete();
            }
        } elseif (count($new_titles) > 0) {
            foreach ($new_titles as $value) {
                DB::table('title_names')->insert(['title_names' => $value]);
            }
        }
        if (count($diff_titles) > 0) {
            foreach ($diff_titles as  $value) {
                DB::table('title_names')->where('title_names', $value)->delete();
            }
        } elseif (count($new_titles) > 0) {
            foreach ($new_titles as $value) {
                DB::table('title_names')->insert(['title_names' => $value]);
            }
        }
        if (count($diff_emp_titles) > 0) {
            foreach ($diff_emp_titles as  $value) {
                DB::table('employee_titles')->where('title', $value)->delete();
            }
        } elseif (count($new_emp_titles) > 0) {
            foreach ($new_emp_titles as $value) {
                DB::table('employee_titles')->insert(['title' => $value]);
            }
        }

        return response()->json(['msg' => 'success']);
    }
}
