<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNotification;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }
    public function login(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|exists:users,email',
                'password' => 'required',
            ]);


            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $credentials = $request->only('email', 'password');
            $user = User::where('email', $credentials['email'])->first();


            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                return $this->responseService->error('Invalid credentials', 401);
            }


            $token = $user->createToken('api-token')->plainTextToken;


            $user->notify((new LoginNotification($user)));
            $data = [
                'user'  => $user,
                'token' => $token,
            ];



            return $this->responseService->success($data, 'Login successful', 200);
        } catch (ValidationException $e) {

            return $this->responseService->error($e->getMessage(), 422);
        } catch (Exception $ex) {

            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
               // 'name'    =>'required',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required',
            ]);


            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Create the user
            $user = User::create([
                //'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);


            $token = $user->createToken('api-token')->plainTextToken;


            $data = [
                'user'  => $user,
                'token' => $token,
            ];
            $user->sendEmailVerificationNotification();


            return $this->responseService->success($data, 'User registration successfully.Please check your email for verification. ', 200);
        } catch (ValidationException $e) {
            // Catch validation exception and return error response
            return $this->responseService->error($e->getMessage(), 422);
        } catch (Exception $ex) {

            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->responseService->success('Logged out',200);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error ' . $ex->getMessage(), 500);
        }
    }
    public function resend(Request $request)
    {
        try {
            $user = Auth::user(); // Get the currently authenticated user

            // Check if the user already has verified email
            if ($user->hasVerifiedEmail()) {
                return $this->responseService->success('Your email is already verified. ', 200);
            }

            // Resend verification email
            $user->sendEmailVerificationNotification();
            return $this->responseService->success('Verification email sent. ', 200);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error ' . $ex->getMessage(), 500);
        }
    }
    public function verify(Request $request, $id, $hash)
    {
        try {
            $user = \App\Models\User::findOrFail($id);

            if (! hash_equals((string) $hash, sha1($user->email))) {
                return $this->responseService->error('Invalid verification link', 400);
            }

            if ($user->hasVerifiedEmail()) {
                return $this->responseService->error('Email is already verified.', 200);
            }

            $user->markEmailAsVerified();

            return $this->responseService->success('Email successfully verified.', 200);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error ' . $ex->getMessage(), 500);
        }
    }
}
