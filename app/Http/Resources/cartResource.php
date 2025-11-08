<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class cartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            "id"=>$this->id,
            "product_id"=>$this->product_id,
            "user_id"=>$this->user_id,
            "quantity"=>$this->quantity,

        ];
    }
}
