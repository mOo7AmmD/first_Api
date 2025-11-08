<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\categoryResource;
use App\Http\Resources\fullProductResource;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;

class webController extends Controller
{
    use apiTrait;

        public function index(){

         $data=Product::with("img","category")->paginate(5);

        if($data->isEmpty()){
            return $this->Query_fails();
        }else{

            return $this->ApiResponse(fullProductResource::collection($data),200,'product is here');
        }
    }

    public function cat(){
        $cat=category::all();
        if($cat->isEmpty()){
            return $this->Query_fails();
        }
        return $this->ApiResponse(categoryResource::collection($cat),200,'category found');
    }

}
