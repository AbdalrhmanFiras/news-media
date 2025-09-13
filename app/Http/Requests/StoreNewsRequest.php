<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
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
            'title' => 'required|string|max:225',
            'content' => 'nullable|string',
            'publish_at' => 'nullable|date',
            'media_type' => 'required|in:image,video',
            'media_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,wmv|max:20480',
        ];
    }
}
