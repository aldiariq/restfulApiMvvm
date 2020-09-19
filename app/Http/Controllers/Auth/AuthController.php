<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:50',
            'password' => 'required|min:8'
        ]);

        if(!$validator->fails()){
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            if($user->save()){
                return response()->json([
                    'isSuccessfull' => true,
                    'message' => "Registrasi Berhasil"
                ], 200);
            }else {
                return response()->json([
                    'isSuccessfull' => false,
                    'message' => "Registrasi Gagal"
                ], 401);
            }
        }else {
            return response()->json([
                'isSuccessfull' => false,
                'message' => "Registrasi Gagal"
            ], 401);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'remember' => 'required|boolean'
        ]);

        if(!$validator->fails()){
            $datauser = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if(Auth::attempt($datauser)){
                $datauserlogin = $request->user();
                $tokenRes = $datauserlogin->createToken('Token Akses');
                $token = $tokenRes->token;

                if($request->remember){
                    $token->expires_at = Carbon::now()->addWeekday(1);
                }

                $token->save();

                return response()->json([
                    'isSuccessfull' => true,
                    'message' => "Login Berhasil",
                    'access_token' => $datauserlogin->createToken('Token Akses')->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenRes->token->expires_at)->toDateString(),
                    'user' => $request->user()
                ]);
            }else {
                return response()->json([
                    'isSuccessfull' => false,
                    'message' => "Login Gagal"
                ], 401);
            }
        }else {
            return response()->json([
                'isSuccessfull' => false,
                'message' => "Login Gagal"
            ], 401);
        }
    }

    public function logout(Request $request){
        if($request->user()->token()->revoke()){
            return response()->json([
                'isSuccessfull' => true,
                'message' => 'Logout Berhasil'
            ]);
        }else {
            return response()->json([
                'isSuccessfull' => false,
                'message' => 'Logout Gagal'
            ]);
        }
    }

    public function user(Request $request){
        return response()->json($request->user());
    }
}
