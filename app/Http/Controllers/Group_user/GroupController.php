<?php namespace App\Http\Controllers\Group_user;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class GroupController extends Controller {


public function allgroups() {
    $groups = Group::all();
    return response()->json($groups, 200);
}

public function getgroup($id){
    $group = Group::find($id);
    if (is_null($group)) {
        return $this->sendError('group not found.');
    }
    return response()->json([
        "success" => true,
        "message" => "Product retrieved successfully ",
        "data" => $group
    ]);
    }

    public function addgroup(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $group = Group::create($input);
        return response()->json([
            "success" => true,
            "message" => "group created successfully.",
            "data" => $group
        ]);
    }

    public function putgroup(Request $request,$id){
        $group = Group::find($id);
        $input = $request->all();
        $group->name = $input['name'];
        $group->description = $input['description'];

        $group->save();
        return response()->json([
            "success" => true,
            "message" => "Product updated successfully.",
            "data" => $group
        ]);
    }
    public function removegroup($id){
        $group = Group::find($id);

        if ($group->delete()) {
            return response()->json(['msg' => 'group has been removed'], 200);
        }
        return response()->json(['msg' => 'Error !!'], 500);
    }

    public function get_category_group(){
        $groups = Group::with('categorie')->get();
        return response()->json([
            "success" => true,
            "message" => "Product updated successfully.",
            "data" => $groups
        ]);
    }

    public function add_category_to_group(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'group_id' => 'required',
            'category_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
            $group=Group::find($request->input('group_id'));
            $category=Category::find($request->input('category_id'));
            $group->categorie()->attach($category);
            return response()->json(["msg" => "category added  to group Succeffully "], 200);


    }

    public function revoke_category_from_group(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'group_id' => 'required',
            'category_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $group=Group::find($request->input('group_id'));
        $category=Category::find($request->input('category_id'));
        if( $group->categorie()->detach($category)){
            return response()->json(["msg" => "category detached  from group Succeffully "], 200);

        }
        return response()->json(["msg" => "Error"], 200);
    }

}
