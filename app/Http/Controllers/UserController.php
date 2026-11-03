<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
        $user = User::where('email', $request->email)->where("password", $request->password)->get();
        $token = $user->createToken()->plainTextRoken();
        return Response('auth_token')->json([
            "user"=> $user,
            "token"=>$token,
            "message"=> "User Logged In successfully"
        ]);
        } catch (Exception $err) {
            return Response()->json([
                "user"=>"",
                "token"=> "",
                "message"=> $err->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=> $request->password,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken();
        return Response()->json([
            "user"=>$user,
            "token"=>$token
        ]);
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
