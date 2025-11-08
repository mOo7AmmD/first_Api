<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\favoritesResource;
use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    use apiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fav=Favorites::all();
        if($fav->isEmpty()){
            return $this->ApiResponse(null,404,"there is no product in fav insert a few");
        }
        return $this->ApiResponse(favoritesResource::collection($fav),200,"fav products found");
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id=auth()->guard("user")->id();
        if(! $user_id){
            return $this->ApiResponse(null,401,"Unauthorized");
        }
        $validate=Validator::make($request->all(),[
            "product_id"=>"required|numeric|exists:products,id"
        ]);
        if($validate->fails()){
            return $this->validated_fails($validate);
        }
        $validated=$validate->validate();

        $is_there=Favorites::where('product_id',$validated['product_id'])
        ->where("user_id",$user_id);
        if($is_there){
            return $this->ApiResponse(null,502,"product is alredy here");
        }

        $validated["user_id"]=$user_id;
        $fav=Favorites::create($validated);

        if(! $fav){
            return $this->Query_fails();

        }
        return $this->ApiResponse(favoritesResource::collection($fav),200,"product inserted successfuly");
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorites $favorites)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $user_id=auth()->guard("user")->id();
        // if(! $user_id){
        //     return $this->ApiResponse(null,401,"Unauthorized");
        // }
        $fav_product=Favorites::find($id);

        if(! $fav_product){
            return $this->ApiResponse(null,404,"product not found in DB");
        }
        try{
            $fav_product->delete();
            return $this->ApiResponse(null,200,"row deleted successfuly");
        }catch (\Exception $e){
            return $this->Query_fails();
        }
    }
}
