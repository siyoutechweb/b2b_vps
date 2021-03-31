<?php namespace App\Http\Controllers\Group_user;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class User_group_Controller extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function add_group_to_user($iduser,$idGroup){
        $user=User::find($iduser);
        $group=Group::find($idGroup);
        //return response()->json([$group->name ." to ".$user->first_name]);
        $user->group()->attach($group);
        return response()->json([
            "success" => true,
            "message" => "group ".$group->name." to ". $user->first_name." .successfully.",
            "data" => $user->group()->get()
        ]);}

    public function get_group_list_by_user($iduser){
        $user=User::find($iduser);/*->with(['group'=>function ($query)
        {$query->with('categorie')->get('');}
        ])->get();*/
        $group_array=[];
        foreach ($user->group as $groupuser) {
            array_push($group_array,$groupuser->name);
        }
       // $user->with('group')->get();
         return response()->json([
             "success" => true,
             "message" => "listed.",
             "data" => $group_array
         ]);
    }
    public function get_category_group_by_user(){
        $user = AuthController::me();
        $catego_list=[];
        foreach ($user->group as $groupuser) {
            $Group=Group::where('id',$groupuser->id)->with(['categorie'=>function ($query)
            {$query->with('subCategories');}
            ])->get();
            array_push($catego_list,$Group);
        }
        return response()->json([
            "success" => true,
            "message" => "category list for user ss ".$user->first_name,
            "data" => $catego_list
        ]);
    }
    }



