<?php

namespace App\Http\Controllers\galleryImagesController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class galleryImagesController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth:api');
    }

    public function get_images_by_user(){
        $supplier = AuthController::me();
        $Images=ProductImage::where('user_id',$supplier->id)->orWhereNull('user_id')->get();
        return response()->json($Images);
    }



    public function deleteImage($id)
    {
        $image = ProductImage::find($id);
        $isImageDeleted = Storage::disk('public')->delete('products/' . $image->image_name);
        if ($isImageDeleted) {
            $image->delete();
            return response()->json(['msg' => 'Image Deleted'], 200);
        }
        return response()->json(['msg' => 'Error'], 500);
    }


    public function uploadImages(Request $request)
    {
        if ($request->hasFile('file')) {
            $supplier= AuthController::me();
            $path = $request->file('file')->store('products', 'public');
            $fileUrl = Storage::url($path);
            $image = new ProductImage();
            $image->image_url = $fileUrl;
            $image->image_name = basename($path);
            $image->user_id=$supplier->id;
            $image->save();
            return response()->json(["id" => $image->id, "image_url" => $fileUrl], 200);
        }
        return response()->json(['msg' => 'Error'], 500);
    }


}
