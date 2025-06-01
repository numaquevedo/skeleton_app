<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRecipesListRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email',
            'keyword' => 'nullable|string',
            'ingredient' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'The email address is not valid.',
            'keyword.string' => 'The keyword must be a valid string.',
            'ingredient.string' => 'The ingredient must be a valid string.',
        ];
    }
}
