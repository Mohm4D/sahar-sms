<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            "first_name"    => "required|max:50|min:2",
            "last_name"     => "required|max:50|min:2",
            "company"       => "required|max:50|min:2",
            "email"        => 'required|email|unique:users,email',
            "password"       => "required|string|confirmed|min:8",
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
            'first_name.required' => trans('validation.first_name.required'),
            'first_name.min' => trans('validation.first_name.min'),
            'first_name.max' => trans('validation.first_name.max'),
            'last_name.required' => trans('validation.last_name.required'),
            'last_name.min' => trans('validation.last_name.min'),
            'last_name.max' => trans('validation.last_name.max'),
            'email.required' => trans('validation.email.required'),
            'email.email' => trans('validation.email.email'),
            'password.required' => trans('validation.password.required'),
            'password.confirmed'   => trans('validation.password.confirmed'),
            'password.min'   => trans('validation.password.min'),
        ];
    }
}
