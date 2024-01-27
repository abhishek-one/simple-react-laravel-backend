<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return $user;
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!is_null($user)) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json(['status' => 'success', 'message' => 'Authenticated']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Password incorrect']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'User does not exists']);
        }
    }
    public function createProduct(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->name = $request->name;

        if(!is_null($request->file('image'))){
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $publicPath = public_path('images');
            $image->move($publicPath, $imageName);
            $product->image = $imageName;
        }

        $product->save();
        return response()->json(['status' => 1, 'data' => $product]);
    }
    public function updateProduct(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::find($request->id);
        $product->name = $request->name;

        if(!is_null($request->file('image'))){
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $publicPath = public_path('images');
            $image->move($publicPath, $imageName);
            $product->image = $imageName;
        }

        $product->save();
        return response()->json(['status' => 1, 'data' => $product]);
    }
    public function viewProducts(Request $request)
    {
        if(is_null($request->input('search'))){
            $products = Product::paginate(5);
        }else{
            $products = Product::where('name', 'LIKE', '%' . $request->input('search') . '%')->paginate(5);
        }
        return response()->json(['data'=>$products]);
    }
    public function deleteProduct(Request $request){
        Product::where('id', $request->id)->first()->delete();
        return response()->json(['status' => 1, 'message' => 'Deleted successfully']);
    }
}
