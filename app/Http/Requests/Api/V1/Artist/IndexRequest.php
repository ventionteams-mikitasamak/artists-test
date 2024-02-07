<?php

namespace App\Http\Requests\Api\V1\Artist;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filter' => 'array:active,email|nullable',
            'filter.active' => 'boolean|nullable',
            'filter.email' => 'string|nullable',
        ];
    }
}
