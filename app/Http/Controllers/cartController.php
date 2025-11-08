<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\cartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class cartController extends Controller
{
    use apiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id=auth()->guard("user")->id();
        if(! $user_id){
            return $this->ApiResponse(null,401,'Unauthorized');
        }
        $cart=Cart::where('user_id',$user_id)->with('product')->get();

        if($cart->isEmpty()){
            return $this->ApiResponse(null,404,"there is no products in DB");
        }
        return $this->ApiResponse(cartResource::collection($cart),200,"data found succsessfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $user_id=auth()->guard("user")->id();
        if(! $user_id){
            return $this->ApiResponse(null,401,'Unauthorized');
        }

        $validated=Validator::make($request->all(),[

            "product_id"=>"required|numeric|exists:products,id",
            "quantity"=>"numeric"
        ]);
        if($validated->fails()){
            return $this->validated_fails($validated);
        }
        $data=$validated->validate();

        $cartItem=Cart::where('user_id',$user_id)
        ->where("product_id",$data['product_id'])->first();

        if($cartItem){
             $cartItem->increment('quantity');
            $cartItem->save();

        }else{
            $data['user_id']=$user_id;
            $cartItem=Cart::create($data);
            if(! $cartItem){
                return $this->Query_fails();
            }
        }
        return $this->ApiResponse(new cartResource($cartItem),200,'data stored succsessflly');

    }



    public function show(string $id)
    {
         $user_id=auth()->guard("user")->id();
         if(! $user_id){
            return $this->ApiResponse(null,401,'Unauthorized');
        }

        $cartItem=Cart::where('user_id',$user_id)
        ->where("product_id",$id)->first();

        if(! $cartItem){
            return $this->ApiResponse(null,404,'undifind product in cart');
        }else{
            return $this->ApiResponse(new cartResource($cartItem),200,"product found");
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if($request->quantity<=0){
            return $this->ApiResponse(null,422,"you cannot dicrese produc's quantity");
        }

        $cartProduct=Cart::find($id);
        if(! $cartProduct){
            return $this->ApiResponse(null,404,"product not found in DB");
        }else{
            $update=$cartProduct->update([
                "quantity"=>$request->quantity
            ]);
            return $this->ApiResponse(new cartResource($cartProduct),200,"quantity updated seccessfuly");
        }

    }

    /**
     * Remove the specified resource from storage.
        */
    public function destroy(string $id)
    {
        $cartItem = Cart::find($id);

        if(!$cartItem){
                return $this->ApiResponse(null, 404, "product not found in cart");
        }

        try {
            $cartItem->delete();
            return $this->ApiResponse(null, 200, "product deleted successfully");
        } catch (\Exception $e) {
            return $this->Query_fails();
        }
    }
}
