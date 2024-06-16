<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try {
            $validateUser = Validator::make(request()->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt(request()->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', request()->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function create()
    {

    }

    public function store()
    {
        try {
            $validateUser = Validator::make(request()->all(), 
            [
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'email' => request('email'),
                'password' => Hash::make(request('password'))
            ]);

            return response()->json([
                'user'=>$user,
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    

    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update($id)
    {
        $user= User::findOrFail($id);

        if(request()->fname != null){
            $user->fname=request()->fname;
        }
        if(request()->mname != null){
            $user->mname=request()->mname;
        }
        if(request()->lname != null){
            $user->lname=request()->lname;
        }
        if(request()->email != null){
            $user->email=request()->email;
        }
        if(request()->contact != null){
            $user->contact=request()->contact;
        }
        if(request()->idNumber != null){
            $user->idNumber=request()->idNumber;
        }
        if((request()->role != null) && ((request()->user()->role) == 'Admin')){
            $user->role=request()->role;
        }
        if(request()->password != null){
            $user->password=Hash::make(request()->password);
        }
        $user->update();
        return response($user,200);
    }

    public function destroy($id)
    {
        
    }
}
