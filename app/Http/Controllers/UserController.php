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
    public function viewProduct(Request $request)
    {
        $product = new Product();
    }
    public function updateProduct(Request $request)
    {
        $product = new Product();
    }
    public function viewProducts(Request $request)
    {
        $products = Product::paginate(5);
        return response()->json(['data'=>$products]);
    }
}
