<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'mobile' => 'required|regex:/(09)[0-9]{9}/',
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
            'mobile.required' => 'وارد کردن شماره تلفن همراه الزامیست',
            'mobile.regex'    => 'شماره تلفن همراه به درستی وارد نشده است',
        ];
    }
}
