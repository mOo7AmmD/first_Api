<?php

namespace App\Http\controllers\Api\trait;

use App\Models\Product;

trait apiTrait
{
public function ApiResponse($data=null, $status=200, $message=null)
{
    $array = [
        "data"    => $data,
        "status"  => $status,
        "message" => $message,
    ];

    return response()->json($array, $status);
}

    public function Query_fails(){

            return $this->ApiResponse(null,404,"data not found");

    }

    public function validated_fails($validated){

            return $this->ApiResponse(null,422,$validated->errors());

    }




}
