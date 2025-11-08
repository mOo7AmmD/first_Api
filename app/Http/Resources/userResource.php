<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'xx'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'gender'=>$this->gender,
            'age'=>$this->age,
            'numper'=>$this->numper
        ];
    }
}
