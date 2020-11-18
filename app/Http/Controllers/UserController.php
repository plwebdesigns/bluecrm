<?php /** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function foo\func;

class UserController extends Controller
{
    /*** Get all dropdowns - mostly used for add sale */
    public function getAllDropdowns()
    {
        $agents = DB::table('users')->get('agent_name');
        $agents_arr = [];
        foreach ($agents as $key => $value) {
            array_push($agents_arr, $value->agent_name);
        }
        sort($agents_arr);
        $cities = DB::table('cities')
            ->get('place_name');
        $type_of_sales = DB::table('type_of_sale')->get('type');
        $mortgage_names = DB::table('mortgage_names')->get('mortgage_names');
        $title_names = DB::table('title_names')->get('title_names');

        return response()
            ->json([
                'agents' => $agents_arr,
                'cities' => $cities,
                'type_of_sales' => $type_of_sales,
                'mortgage_names' => $mortgage_names,
                'title_names' => $title_names
            ]);
    }

    public function getAgentTitles(Request $request){
        $agent_titles = DB::table('employee_titles')->get('title');

        return response()->json(['agent_titles' => $agent_titles]);
    }

    /*** Register a new agent
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $input = $request->input();
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3', 'unique:users,agent_name'],
            'phone' => ['required', 'string', 'max:10'],
            'title' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'confirm_pass' => ['required', 'min:8', 'same:password']
        ], [
            'phone.numeric' => 'No dashes or parentheses in the Phone field',
            'phone.max' => 'No more than 10 numbers for the Phone field'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 412);
        }

        // Format phone number
        $phone =
            substr($input['phone'],0, 3) . '-' .
            substr($input['phone'],3, 3) . '-' .
            substr($input['phone'],6);
        $sql_arr = [
            'agent_name' => $input['name'],
            'title' => $input['title'],
            'phone' => $phone,
            'email' => $input['email'],
            'dob' => $input['dob'],
            'isAdmin' => (int)$input['isAdmin'],
            'isManager' => (int)$input['isManager'],
            'password' => Hash::make($input['password']),
            'api_token' => Str::random(60),
            'added_by' => $request->user()->agent_name
        ];

        User::create($sql_arr);

        return response()->json(['msg' => 'success']);
    }

    // Delete an agent
    public function deleteAgent(Request $request){
        $agent = User::find($request->id);

        try {
            $agent->delete();
        } catch (\Exception $exception){
            return response()->json(['errors' => $exception->getMessage()]);
        }
        return response()->json(['msg' => "Successfully deleted"]);
    }

    /*** Allow the user to change their login password
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        $input = $request->input();
        $validator = Validator::make($input, [
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword'
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()->all()]);
        } elseif (Hash::check($input['currentPassword'], $user->password)) {
            $user->password = Hash::make($input['newPassword']);
            $user->save();

            return response()->json(['msg' => "Password successfully changed"]);
        }
        return response()->json(["msg" => "Something went wrong"]);
    }

    /*** Get agent profile - Picture only
     * @param Request $request
     * @return JsonResponse
     */
    public function getAgentProfile(Request $request)
    {
        $id = $request->user();
        $id = $id->id;
        $user = DB::table('users')->where('id', $id)->first(
            [
                'agent_name',
                'title',
                'phone',
                'email',
                'picture_url'
            ]
        );

        return response()->json(['agent' => $user]);
    }

    /*** Upload profile picture
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request){
        $agent = User::find($request->input('id'));
        $path = $request->file('profile_pic')->store('public/profile_pictures');
        $agent->picture_url = str_replace('public/', '', $path);
        $agent->save();

        return response()->json(['msg' => 'Successfully Uploaded!']);
    }

    /*** Get full agent profile
     * @param Request $request
     * @return JsonResponse
     */
    public function getFullProfile(Request $request){
        $user = $request->user();
        $ignored_agents = DB::table('ignored_agents')->get('agent_name');
        $agents_ignored = $ignored_agents->map(function ($item){
            return $item->agent_name;
        });
        $agents = User::all()->whereNotIn('agent_name', $agents_ignored);

        /*** YTD sales for each agent and the sum
         * of their sale credit on each sale
         * to determine their YTD rank
         * */
        $all_ytd_sales = $agents->map(function ($item){
            $year = \date('Y');
            return $item->sales
                ->whereBetween('closing_date', ["{$year}-01-01", "{$year}-12-31"])
                ->sum('pivot.sale_credit');
        });
        $temp_arr = $all_ytd_sales->toArray();
        arsort($temp_arr);
        $i = array_search(($user->id)-1, array_keys($temp_arr));
        $rank_ytd = $i + 1;

        /*** Sales for the current quarter and the
         * sum of sale credit for each user/agent to
         * determine the agent's quarter rank
         * */
        $cur_quarter_sales = $agents->map(function ($item){
            $year = \date('Y');
            $month = \date('m');

            if ($month >= 1 and $month < 4):
                $x = "{$year}-01-01";
                $y = "{$year}-03-31";
            elseif ($month >= 4 and $month < 7):
                $x = "{$year}-04-01";
                $y = "{$year}-06-31";
            elseif ($month >= 7 and $month < 10):
                $x = "{$year}-07-01";
                $y = "{$year}-09-31";
            else:
                $x = "{$year}-10-01";
                $y = "{$year}-12-31";
            endif;

            return $item->sales
                ->whereBetween('closing_date', [$x, $y])
                ->sum('pivot.sale_credit');
        });
        $temp_arr = $cur_quarter_sales->toArray();
        arsort($temp_arr);
        $i = array_search(($user->id)-1, array_keys($temp_arr));
        $rank_cur_quarter = $i + 1;

        // Make any adjustments before values
        // are sent to the client side
        if ($user->phone === null):
            $user->phone = '555-555-5555';
        elseif (strlen($user->phone) === 10):
            $user->phone = substr($user->phone,0, 3) . '-' .
                substr($user->phone,3, 3) . '-' .
                substr($user->phone,6);
        endif;
        $user->current_split = floatval($user->current_split) * 100;

        return response()->json(['agent' => $user->only([
            'id',
            'title',
            'agent_name',
            'dob',
            'phone',
            'email',
            'current_split',
            'picture_url',
            'isAdmin'
        ]),
            'ytd_rank' => $rank_ytd,
            'cur_quarter_rank' => $rank_cur_quarter
        ]);
    }

    /*** Update Agent Information via Profile page
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request){
        $user = $request->user();
        $input = $request->input('agent');
        $rules = [];
        $data = [];

        if ($input['email'] !== $user->email):
            $data['email'] = $input['email'];
            $rules['email'] = 'email|max:255|unique:users';
            $user->email = $input['email'];
        endif;
        if ($input['password'] !== null):
            $data['password'] = $input['password'];
            $rules['password'] = 'min:8';
            $user->password = Hash::make($input['password']);
        endif;
        if ($input['phone'] !== '5555555555'):
            $data['phone'] = $input['phone'];
            $rules['phone'] = 'string|size:10';
            $user->phone =
                substr($input['phone'],0, 3) . '-' .
                substr($input['phone'],3, 3) . '-' .
                substr($input['phone'],6);
        endif;
        if (collect($input['dob'])->isNotEmpty() and $input['dob'] !== 'N/A'):
            $user->dob = \date('m-d', strtotime($input['dob']));
        endif;
        if (collect($rules)->isNotEmpty() and collect($data)->isNotEmpty()):
            $validator = Validator::make($data, $rules);
            if ($validator->fails()):
                return response()->json(['errors' => $validator->errors()->all()], 412);
            endif;
        endif;

        $user->save();

        return response()->json(['msg' => 'Successfully updated!']);
    }
}
