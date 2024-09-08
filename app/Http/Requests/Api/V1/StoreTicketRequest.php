<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\Api\V1\Abilities;
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

        // $rules = [
        //     "data.attributes.title" => 'required|string',
        //     "data.attributes.description" => 'required|string',
        //     "data.attributes.status" => 'required|string|in:A,C,H,X',
        // ];

        // if ($this->routeIs('tickets.store')) {
        //     $rules["data.relationship.author.data.id"] = 'required|integer';
        // }

        // if ($this->routeIs('tickets.store')) {
        //     if ($user->tokenCan(Abilities::CREATE_OWN_TICKET)) {
        //         $rules["data.relationship.author.data.id"] .= '|size:' . $user->id;
        //     }
        // }

        $authorAttribute = $this->routeIs('tickets.store') ? 'data.relationship.author.data.id' : 'author';
        $user = $this->user();

        $rules = [
            "data.attributes.title" => 'required|string',
            "data.attributes.description" => 'required|string',
            "data.attributes.status" => 'required|string|in:A,C,H,X',
            $authorAttribute => 'required|integer|exists:users,id|size:' . $user->id,
        ];

        if ($user->tokenCan(Abilities::CREATE_TICKET)) {
            $rules[$authorAttribute] .= 'required|integer|exists:users,id';
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'author' => $this->route('author')
            ]);
        }
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
