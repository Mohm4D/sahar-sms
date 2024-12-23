<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\V1\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends AppBaseController
{

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'company' => $request->get('company'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        return $this->response->success(new UserResource($user));
    }

    public function profile()
    {
        $profile = new UserResource(Auth::user());
        return $this->response->success($profile);
    }
}
