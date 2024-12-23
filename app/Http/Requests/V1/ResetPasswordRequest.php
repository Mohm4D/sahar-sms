<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ];
    }

    /**
     * return validations custom messages function
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'email.required' => 'وارد کردن ایمیل الزامیست',
            'email.email'    => 'فرمت ایمیل وارد شده نادرست است',
            'token.required' => 'وارد کردن توکن الزامیست',
            'password.required' => 'وارد کردن پسورد الزامیست',
            'password.min'    => 'پسورد میبایست حداقل هشت کاراکتر باشد',
            'password.confirmed'    => 'تکرار پشورد مطابقت ندارد',
        ];
    }
}
