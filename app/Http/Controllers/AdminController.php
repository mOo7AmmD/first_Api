<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResorce;
use App\Models\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use apiTrait;

    public function index(){
      $data =admin::all();
        if($data){
            return $this->ApiResponse($data=ApiResorce::collection(admin::all()),200,"succes");
        }else{
            return $this->ApiResponse(null,404,"failed");
        }

    }

    public function store(Request $request){
        $validated=Validator::make($request->all(),[
            "name"=>"required|min:3",
            "email"=>"required|email|unique:admins,email",
            "password"=>"required|min:6",
            "age"=>"required|max:100",
            "gender"=>"required|in:male,female",
        ]);

        if($validated->fails()){
            return $this->ApiResponse(null,422,$validated->errors());
        }

        $data=admin::create($validated->validated());
        if(! $data){
            return $this->ApiResponse($data,$status=404,$message="data didn't saved  ");
        }else{

            return $this->ApiResponse($data=new ApiResorce($data),$status=201,$message="data saved success");
        }

    }

    public function select(int $id){
        $data=admin::find($id);

        if(! $data){

            return $this->Query_fails($data);
        }else{

            return $this->ApiResponse($data=new ApiResorce(admin::find($id)),200,"product is here");
        }


    }

    public function update(Request $request ,int $id){
        $validated=validator::make($request->all(),[
            "name"=>"required|min:3",
            "email"=>"required|max:255|unique:admins,email,".$id,
            "age"=>"required|max:100",
            "gender"=>"required|in:male,female"
        ]);
        if($validated->fails()){
            return $this->validated_fails($validated);
        }
        $data=admin::find($id);
        if(! $data){
            return $this->Query_fails(null);
        }else{

            $updated=$data->update($validated->validated());
            if($updated){
                return $this->ApiResponse($updated,201,'data updated succesefuly');
            }
        }




    }

    public function destroy(int $id){
        $data= admin::find($id);
        if(Auth()->id() == $data->id){


        if(! $data){
            return $this->Query_fails();
        }else{
            $data->delete($id);
            if($data){
                return $this->ApiResponse(null,200,'admin deleted well');
            }
        }

    }else{
        return $this->ApiResponse(null,500,'u have no acsess to del that admin');
    }
}
}
