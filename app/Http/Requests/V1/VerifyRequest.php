<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
            'mobile'    => 'required|regex:/(09)[0-9]{9}/',
            'code'      => 'required',
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
            'mobile.required'   => trans('validation.mobile.required'),
            'mobile.regex'      => trans('validation.mobile.regex'),
            'code.required'     => trans('validation.verification-code.required'),
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'mobile'    => 'تلفن همراه',
            'code'      => 'کد ورود',
        ];
    }

}
