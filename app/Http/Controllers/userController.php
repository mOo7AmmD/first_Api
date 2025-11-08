<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\userResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    use apiTrait;

    public function index()
    {
        $users=User::get();
       if($users->isEmpty()){
        return $this->ApiResponse(null,404,'there is no data in DB');
    }
    return $this->ApiResponse(userResource::collection($users),200,'data is here');
}

public function edit(int $id){
    $user=User::find($id);
    if(! $user){
        return $this->ApiResponse(null,404,'there is no data in DB');
    }

    return $this->ApiResponse(new userResource($user),200,'data is here');


}

public function update(Request $request, int $id){
    $user=User::find($id);
    if(! $user){
        return $this->ApiResponse(null,404,'user not found in DB');
    }
         $validated=validator::make($request->all(),[

            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email,'.$id,
            'gender'   => 'required|in:male,female',
            'number'   => 'required|numeric',
            'age'      => 'required|numeric|min:10|max:100',
        ]);

        if($validated->fails()){
            return $this->validated_fails($validated);
        }
        $Vdata= $validated->validated();

        $userUpdat= $user->update($Vdata);

        if(! $userUpdat){
            return $this->ApiResponse(null,502,'somthing went wrong try again latter');
        }
        return $this->ApiResponse(null,200,'user updated sucssfully');


}

public function delete(int $id){

        $user=User::find($id);
        if(! $user){
            return $this->ApiResponse(null,404,'user is not found');

        }
        $del=$user->delete();
        if($del){
            return $this->ApiResponse(null,200,'user deleted sucss');
        }
        return $this->ApiResponse(null,500,'somthing wrong happend plese try again latter');


}

}
