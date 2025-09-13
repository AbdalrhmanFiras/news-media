<?php

namespace App\Http\Requests;

use App\Enum\Governorate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'age' => 'sometimes|integer',
            'gender' => 'sometimes|string|in:male,female',
            'governorate' => [
                'sometimes',
                'nullable',
                Rule::in(array_column(Governorate::cases(), 'value'))
            ],

            'city' => 'sometimes|string',
            'address' => 'sometimes|string',
            'bith_date' => 'sometimes|date',
            'zip_code' => 'sometimes|integer',
            'avatar' => 'sometimes|file|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ];
    }
}
