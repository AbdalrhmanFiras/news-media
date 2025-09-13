<?php

namespace App\Http\Requests;

use App\Enum\Governorate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserProfileRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string|in:male,female',
            'governorate' => [
                'required',
                'nullable',
                Rule::in(array_column(Governorate::cases(), 'value'))
            ],

            'city' => 'required|string',
            'address' => 'required|string',
            'bith_date' => 'required|date',
            'zip_code' => 'required|integer',
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif|max:20480',

        ];
    }
}
