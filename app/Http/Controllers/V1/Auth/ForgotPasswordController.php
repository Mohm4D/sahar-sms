<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\V1\ResetPasswordRequest;
use App\Http\Requests\V1\ّForgetPasswordRequest;
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
        return $status === Password::RESET_LINK_SENT ? response()->json(['message' => __($status)], 200) : response()->json(['email' => __($status)], 400);
    }

    /**
     * Handle a request to reset the password. *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill(['password' => bcrypt($password)])->save();
        });
        return $status === Password::PASSWORD_RESET ? response()->json(['message' => __($status)], 200) : response()->json(['email' => __($status)], 400);
    }
}
