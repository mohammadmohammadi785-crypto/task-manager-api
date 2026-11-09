<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){}

    public function login(Request $request){
        if(!Auth::attempt($request->only("email", "password"))){
            return Response()->json([
                "state"=> false,
                "mesage"=> "In valid email or password"
            ]);
        }
        $user = User::where("email", $request->email)->first();
        $token = $user->createToken("auth_token")->plainTextToken;
        return Response()->json([
            "state"=> true,
            "message"=> "User logged in successfully",
            "user"=> $user,
            "token"=> $token,   
        ], 200);
        
        // try{
        //     $user = User::where("email", $request->email)->first();
        //     if($user && Hash::check($request->password, $user->password)){
        //         $token = $user->createToken("auth_token")->plainTextToken;
        //         return Response()->json([
        //             "state"=> true,
        //             "message"=> "User logged in successfully",
        //             "user"=> $user,
        //             "token"=> $token,
        //         ], 200);
        //     };
        // } catch (Exception $err){
        //     return Response()->json([
        //         "state"=> false,
        //         "message"=> $err->getMessage(),
        //         "user"=> null,
        //         "token"=> null,
        //     ], 400); 
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=> Hash::make($request->password),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken();
        return Response()->json([
            "user"=>$user,
            "token"=>$token
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return Response()->json([
            "user"=> $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $updateUser = $user->update([
            "name"=> $request->name,
            "email"=>$request->email,
            "password"=> $request->password,
        ]);
        return Response()->json([
            "user"=> $updateUser,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return Response()->json([
                "message"=> "User Deleted Successfully",
            ]);
        } catch (Exception $err) {
            return Response()->json([
                "message"=> $err->getMessage(),
            ]);
        }
    }
}
