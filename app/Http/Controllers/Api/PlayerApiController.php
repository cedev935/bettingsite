<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPlayerApiRequest;
use App\Http\Requests\RegisterPlayerApiRequest;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PlayerApiController extends Controller
{
    public function register(RegisterPlayerApiRequest $request)
    {
        $player = Player::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'balance'=> "1000"
        ]);

        $access_token = $player->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $access_token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(LoginPlayerApiRequest $request)
    {
        $player = Player::where('email', $request->email)->first();

        if ($player ) {
            if (Hash::check($request->password, $player ->password)) {
                \auth()->login($player ,true);

                $access_token = $player
                    ->createToken('auth_token')
                    ->plainTextToken;

                return response()->json([
                    'player'=>$player ,
                    'token' => $access_token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                return response()->json(['message' => 'Password mismatch'], 422);
            }
        } else {
            return response()->json(['message' => 'User EMAIL does not exists!'], 422);
        }
    }
    public function logout(): JsonResponse
    {
        $player = Auth::user();
        $player->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
