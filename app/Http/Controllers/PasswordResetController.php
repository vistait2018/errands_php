<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }


    public function sendResetLinkEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['email' => 'required|email|exists:users,email']);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? $this->responseService->success(_('Password reset link sent to your email.'), 200)
                : $this->responseService->error(_('We could not find a user with that email address.'),  422);
        } catch (ValidationException $e) {
            return $this->responseService->error($e->getMessage(), 422);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error ' . $ex->getMessage(), 500);
        }
    }

    public function reset(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token'    => 'required',
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();

                    $user->setRememberToken(Str::random(60));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? $this->responseService->success(_('Password has been reset successfully'), 200)
                : $this->responseService->error(_('Password  reset not  successful'),  422);
        } catch (ValidationException $e) {

            return $this->responseService->error($e->getMessage(), 422);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error ' . $ex->getMessage(), 500);
        }
    }
}
