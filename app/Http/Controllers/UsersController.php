<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return http::respuesta(http::retError, $validator->errors());
        }

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->save();
        return Http::respuesta(http::retOK, "funcionando");
    }

    public function login(Request $request){
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credenciales)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            return http::respuesta(http::retOK, ['token' => $token])->withoutCookie($cookie);
        } else {
            return http::respuesta(http::retDenyBot, "no autorizado");
        }
    }

    public function userProfile(Request $request){
        return http::respuesta(http::retOK, [
            'message' => 'logeado',
            'user' => auth()->user()
        ]);
    }
}
