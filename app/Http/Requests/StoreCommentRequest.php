<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->id;
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            $response = ApiResponse::send(422, 'Validation error', $validator->errors());

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
            'body' => ['required', 'min:3', 'max:100'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'parent_id' => ['integer', 'exists:comments,id'],
        ];
    }
}
