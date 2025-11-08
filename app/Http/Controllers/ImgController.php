<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\imgResource;
use App\Models\img;
use Illuminate\Http\Request;

class ImgController extends Controller
{
    use apiTrait;
    public function index(){
        $data= img::get();
        if($data->isEmpty()){
            return $this->Query_fails();
        }else{
            return $this->ApiResponse(imgResource::collection($data),200,'data us here');
        }
    }
}
