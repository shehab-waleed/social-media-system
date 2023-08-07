<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class StoreRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            $response = ApiResponse::send(422, 'Validation Errors', $validator->errors());

            throw new ValidationException($validator, $response);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */

    public function rules(): array
    {
        return [
            //
            'first_name' => ['string' , 'required' , 'min:3' , 'max:24'],
            'last_name' => ['string' , 'required' , 'min:3' , 'max:24'],
            'username' => ['string' ,'unique:users,username' , 'required' , 'min:3' , 'max:24'],
            'email' => ['email' , 'required' ,'unique:users,email'],
            'password' => ['required', 'confirmed' , Rules\Password::defaults()],
            'is_admin' => ['boolean']

        ];
    }
}
