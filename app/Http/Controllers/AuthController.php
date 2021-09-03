<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Models\Author;

class AuthController extends Controller
{

    /**
     * Login author to system with session
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginAdmin(Request $request)
    {
        //find current user
        $author = Author::where('username', $request->username)->first();

        //check if it is admin user
        if(!$author->admin)
            return response()->json([
                'success' => false,
                'message' => 'User has no admin permission'
            ]);


        //check password
        if(!Auth::attempt([
            'username' => $request->username, 
            'password' => $request->password])) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Wrong user credentials'
            ]);
        }

        $response = [
            'success' => true,
            'author' => $author,
        ];
        return response()->json($response);
    }

    /**
     * Login author to system with token
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // find user
        $author = Author::where('username', $request->username)->first();

        // Check password
        if(!$author || !Hash::check($request->password, $author->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong user credentials'
            ]);
        }

        //generate token
        $token = $author->createToken('myapptoken')->plainTextToken;

        $response = [
            'success' => true,
            'author' => $author,
            'token' => $token
        ];
        return response()->json($response);
    }


    /**
     * Logout author from system
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $req)
    {
        try {
			Auth::guard('web')->logout();            
			$success = true;

			Auth::user()->tokens()->delete();

            $message = 'Successfully logged out';
        } catch (QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }
}
