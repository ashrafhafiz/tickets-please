<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes()
    {
        $attributeMap = [
            "data.attributes.title" => 'title',
            "data.attributes.description" => 'description',
            "data.attributes.status" => 'status',
            "data.attributes.createdAt" => 'created_at',
            "data.attributes.updatedAt" => 'updated_at',
            "data.relationship.author.data.id" => 'author_id'
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }
        return $attributesToUpdate;
    }

    public function messages(): array
    {
        return [
            "data.attributes.title" => 'Title is a required field.',
            "data.attributes.description" => 'Title is a required field.',
            "data.attributes.status" => 'Invalid status. Please use A, C, H, or X.',
            "data.relationship.author.data.id" => 'Title is a required field.'
        ];
    }
}
