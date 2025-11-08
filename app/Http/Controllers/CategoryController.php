<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\categoryResource;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class CategoryController extends Controller
{
    use apiTrait;

    public function index(){
          $data=category::get();
        if($data->isEmpty()){
            return $this->Query_fails();
        }else{
            return $this->ApiResponse( categoryResource::collection($data),200,"data here");
        }
    }

    public function store(Request $request){
        $validated=Validator::make($request->all(),[

            "name"=>"required|between:2,50|unique:categories,name"
        ]);

        if($validated->fails()){
            return $this->ApiResponse("null",404,$validated->errors());
        }

        $data=category::create($validated->validate());

        if(! $data){
            return $this->Query_fails();
        }else{
            return $this->ApiResponse(new categoryResource($data),200,"data succes added");
        }
    }

    public function show(int $id){
        $cat=category::find($id);
        if(! $cat){
            return $this->ApiResponse(null,404,'cat not found in DB');
        }
        return $this->ApiResponse(new categoryResource($cat),200,'product found');
    }
    public function update(Request $request , int $id){
        $cat=category::find($id);
        if(! $cat){
            return $this->ApiResponse(null,404,'categoriy not found in DB');
        }
          $validated=Validator::make($request->all(),[

            "name"=>"required|between:2,50|unique:categories,name,".$id
        ]);

        if($validated->fails()){
            return $this->ApiResponse("null",404,$validated->errors());
        }

        $update=$cat->update($validated->validate());
        if(! $update){
            return $this->Query_fails();
        }
        return $this->ApiResponse(null,200,'cat updated succsess');
    }

    public function destroy(int $id){
        if($id){
            $cat=category::find($id);
            if(! $cat){
                return $this->ApiResponse(null,404,"category not found");
            }else{
                $done= $cat->delete($id);
                if(! $done){
                    return $this->Query_fails();
                }
                return $this->ApiResponse(null,200,"category deleted succes");
            }
        }
    }
}
