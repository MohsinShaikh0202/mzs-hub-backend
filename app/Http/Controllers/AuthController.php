<?php

namespace App\Http\Controllers;

use App\Models\MzsHubUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Register a user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:mzs_hub_users',
            'email' => 'required|email|unique:mzs_hub_users',
            'password' => 'required|min:6',
            'mobile_number' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return error_response('Validation error', 422, $validator->errors());
        }

        $user = MzsHubUsers::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_number' => $request->mobile_number,
            'is_active' => true,
        ]);

        return success_response('User registered successfully', $user);
    }

    // Login with email and password
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return error_response('Invalid credentials', 401);
        }

        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ]);
    }

    // Send OTP to email
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:mzs_hub_users,email',
        ]);

        if ($validator->fails()) {
            return error_response('Validation error', 422, $validator->errors());
        }

        $otp = rand(100000, 999999); // Generate 6-digit OTP

        $user = MzsHubUsers::where('email', $request->email)->first();
        $user->update(['otp' => $otp]);

        Mail::raw("Your OTP is $otp", function ($message) use ($user) {
            $message->to($user->email)->subject('OTP Verification');
        });

        return success_response('OTP sent to your email', []);
    }

    // Verify OTP and login
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:mzs_hub_users,email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return error_response('Validation error', 422, $validator->errors());
        }

        $user = MzsHubUsers::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$user) {
            return error_response('Invalid OTP', 401, []);
        }

        $token = JWTAuth::fromUser($user);
        $user->update(['otp' => null]); // Clear OTP after verification

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
