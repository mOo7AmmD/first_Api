<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            "xx"=>$this->id,
            "name"=>$this->name,
            "info"=>$this->info,
            "price"=>$this->price,
            'sale'=>$this->sale,
            'amount'=>$this->amount,
            'category_id'=>$this->cat_id
        ];
    }
}
