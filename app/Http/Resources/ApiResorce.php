<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            "XX"=>$this->id,
            "name"=>$this->name,
            "email"=>$this->email,
            "age"=>$this->age,
            "gender"=>$this->gender,
        ];
    }
}
