<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ّForgetPasswordRequest extends FormRequest
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
            'email' => 'required|email'
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
        ];
    }
}
