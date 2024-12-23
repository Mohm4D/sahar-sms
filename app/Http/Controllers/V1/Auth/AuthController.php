<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\VerifyRequest;
use App\Jobs\SendSMSVerification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class AuthController extends AppBaseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function register(RegisterRequest $request)
    {
        // ثبت نام میبایست با یک شماره موبایل شروع شود

        $data = json_decode(Redis::get('temp_data:' . $request->mobile));
        if ($data && $data->attempt > 5){
            return $this->response->error('به دلیل سعی بیش از حد لطفا بعدا اقدام کنید .', Response::HTTP_FORBIDDEN);
        }

        $user = User::whereMobile($request->mobile)->first();
        if ($user) {
            return $this->response->error('این شماره موبایل قبلا ثبت شده است .', Response::HTTP_CONFLICT);
        }

        User::create([
            'mobile' => $request->get('mobile'),
            'status' => 'pre_signup'
        ]);

        $smsCode = $this->generateVerifyCode();
        $tempData = [
            'mobile_number' => $request->mobile,
            'sms_code' => $smsCode,
            'time' => Carbon::now()->toTimeString(),
            'attempt' => $data != null ? ++$data->attempt : 1
        ];

        Redis::set('temp_data:' . $request->mobile, json_encode($tempData), 'EX', 120);

        SendSMSVerification::dispatch($request->mobile, $smsCode);
        return $this->response->success(null, 200);
    }

    public function verify(VerifyRequest $request)
    {
        try {

            $temp = json_decode(Redis::get('temp_data:' . $request->mobile));

            if (!$temp or $temp->sms_code != $request->code)
                return $this->response->error('متاسفیم ، کد ورود اشتباه است', Response::HTTP_FORBIDDEN);

            if ($temp && $temp->attempt > 5)
                return $this->response->error('به دلیل سعی بیش از حد لطفا بعدا اقدام کنید .', Response::HTTP_FORBIDDEN);

            $user = User::whereMobile($request->mobile)->first();

            if (!$user)
                return $this->response->error('خطایی پیش آمده مجددات اقدام کنید', Response::HTTP_NOT_FOUND);

            $token = $user->createToken('Personal Access Token');

            $result=[
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_in' => Carbon::createFromFormat('Y-m-d H:i:s', $token->token->expires_at)->toDateTimeString(),
                'user' => $user,
            ];

            if ($user->status == 'pre_signup')
                $user->status = 'verified';
                $user->save();

            json_decode(Redis::del('temp_data:' . $request->mobile));
            return $this->response->success($result,  200);

        } catch (Exception $exception) {
            if ($exception->getMessage() == "Trying to get property 'id' of non-object") {
                return $this->response->error($exception->getMessage(), 500);
            }
            throw $exception;
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::whereMobile($request->mobile)->first();

        if ($user && $user->status != 'active') {
            return $this->response->error('متاسفیم ، حساب کاربری شما غیر فعال است .', Response::HTTP_CONFLICT);
            /** user not exists */
        } elseif (!$user) {
            return $this->response->error(["error" => "کاربری با این مشخصات یافت نشد ."], 404);
        }

        if (!Gate::allows('is-member', $user)) {
            Log::info("تشخیص کاربر با نقش ممبر ");
        }

        if ($request->filled('password')) {
            if (Hash::check($request->password, $user->password)) {

                $token = $user->createToken('Personal Access Token');
                $result=[
                    'access_token' => $token->accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => Carbon::createFromFormat('Y-m-d H:i:s', $token->token->expires_at)->toDateTimeString(),
                    'user' => $user,
                ];

                json_decode(Redis::del('temp_data:' . $request->mobile));
                return $this->response->success($result,  200);
            }
            return response()->json(['message' => 'Invalid password'], 401);
        }

        if ($request->filled('otp')) {
            if ($this->verifyOtp($user, $request->otp)) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(['message' => 'Login successful', 'token' => $token], 200);
            }
            return response()->json(['message' => 'Invalid OTP'], 401);
        }


        $data = json_decode(Redis::get('temp_data:' . $request->mobile));

        $smsCode = $this->generateVerifyCode();
        $tempData = [
            'mobile_number' => $request->mobile,
            'sms_code' => $smsCode,
            'time' => Carbon::now()->toTimeString(),
            'attempt' => $data != null ? ++$data->attempt : 1
        ];
        Redis::set('temp_data:' . $request->mobile, json_encode($tempData), 'EX', 120);

        SendSMSVerification::dispatch($request->mobile, $smsCode);
        return $this->response->success(null, 200);

    }

    private function generateVerifyCode(): int
    {
        if (App::environment('local', 'staging', 'testing')) {
            $randomCode = (int)Carbon::now()->format('ym');
        }else{
            $randomCode = random_int(1000, 9999);
        }
        return $randomCode;
    }



}
