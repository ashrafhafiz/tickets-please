<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'isManager' => $this->is_manager,
//                'emailVerifiedAt' => $this->when(
//                    $request->routeIs('users.*'),
//                    $this->email_verified_at
//                ),
//                'CreatedAt' => $this->when(
//                    $request->routeIs('users.*'),
//                    $this->created_at
//                ),
//                'UpdatedAt' => $this->when(
//                    $request->routeIs('users.*'),
//                    $this->updated_at
//                ),
                $this->mergeWhen($request->routeIs('authors.*'),[
                    'emailVerifiedAt' => $this->email_verified_at,
                    'CreatedAt' => $this->created_at,
                    'UpdatedAt' => $this->updated_at
                ])
            ],
            'includes' => TicketResource::Collection($this->whenLoaded('tickets')),
            'links' => [
                'self' => route('authors.show',['author' => $this->id])
            ]
        ];
    }
}
