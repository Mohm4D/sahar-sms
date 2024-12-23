<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\V1\ResetPasswordRequest;
use App\Http\Requests\V1\ّForgetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends AppBaseController
{
    /**
     * Handle a request to send a reset link to the given email. *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(ّForgetPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'لینک ریست پسورد به ایمیل شما ارسال شد'], 200);
        }

        return response()->json(['message' => 'خطایی رخ داده مجددا اقدام کنید'], 500);

    }

    /**
     * Handle a request to reset the password. *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'پسورد با موفقیت تغیر یافت'], 200);
        }

        return response()->json(['message' => 'خطایی رخ داده مجددا اقدام کنید'], 500);
    }
}
