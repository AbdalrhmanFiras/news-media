<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required|string',
            'body' => 'required|string',
            'media' => 'required|array|min:1',
            'media.*.type' => 'required|in:image,video',
            'media.*.file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,wmv|max:20480',
        ];
    }
}
