<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends BaseTicketRequest
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
//        return [
//            "data.attributes.title" => 'required|string',
//            "data.attributes.description" => 'required|string',
//            "data.attributes.status" => 'required|string|in:A,C,H,X',
//            "data.relationship.author.data.id" => 'required|integer'
//        ];

        $rules = [
            "data.attributes.title" => 'required|string',
            "data.attributes.description" => 'required|string',
            "data.attributes.status" => 'required|string|in:A,C,H,X',
        ];

        if ($this->routeIs('tickets.store')) {
            $rules["data.relationship.author.data.id"] = 'required|integer';
        }

        return $rules;
    }

    // public function messages(): array
    // {
    //     return [
    //         "data.attributes.title" => 'Title is a required field.',
    //         "data.attributes.description" => 'Title is a required field.',
    //         "data.attributes.status" => 'Invalid status. Please use A, C, H, or X.',
    //         "data.relationship.author.data.id" => 'Title is a required field.'
    //     ];
    // }
}