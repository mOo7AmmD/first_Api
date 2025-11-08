<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\ApiResorce;
use App\Http\Resources\fullProductResource;
use App\Http\Resources\productResource;
use App\Models\category;
use App\Models\img;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    use apiTrait;

    public function index(){
         $data=Product::with("img","category")->get();
        if($data->isEmpty()){
            return $this->Query_fails();
        }else{

            return $this->ApiResponse(fullProductResource::collection($data),200,'product is here');
        }

    }

    public function store(Request $request){
        $validate=Validator::make($request->all(),[
            "name"=>'required|min:3',
            "info"=>'required|min:6',
            "price"=>'required',
            "sale"=>'required|max:100',
            "amount"=>'required',
             "cat_name"=>"required",
             "img.*" => "required|image|mimes:jpg,jpeg,png|max:2048"




        ]);
        if($validate->fails()){
            return $this->ApiResponse(null,422,$validate->errors());
        }

        $validated=$validate->validate();
        $cate_name=$validated['cat_name'];
        $cat=category::where("name",$cate_name)->first();
        if(! $cat){
            return $this->ApiResponse(null,404,'category not found in database');
        }
        //

             $data=Product::create([
                'name'=>$validated['name'],
                'info'=>$validated['info'],
                'price'=>$validated['price'],
                'sale'=>$validated['sale'],
                'amount'=>$validated['amount'],
                'cat_id'=>$cat->id,

            ]);
            if(! $data){
                return $this->Query_fails();
            }


        if($request->hasFile('img')){
            try {

                foreach ($request->file('img') as $img) {
                    $path=$img->store("image","public");
                   $save_img= img::create([
                        'name'=>$path,
                        'product_id'=>$data->id
                    ]);
                    if(! $save_img){
                        return $this->Query_fails();
                    }
                }
            } catch (\Illuminate\Database\QueryException $th) {
            return $this->ApiResponse(null, 500, 'Image upload failed');
            }





                return $this->ApiResponse(new productResource($data),200,'data saved succes');
            }
        //




    }
    public function edit(int $id){

             $data=Product::where('id',$id)->with("img","category")->first();
             if(! $data){

                 return $this->Query_fails();
             }
                return $this->ApiResponse(new fullProductResource($data),200,'product with you');
        }

    public function update(Request $request, int $id){
        $validate=Validator::make($request->all(),[
            'name'=>"required|string|between:3,40",
            "info"=>'required|string|min:6',
            "price"=>'required|numeric|min:0',
            "sale"=>'required|numeric|between:0,100',
            "amount"=>'required|integer|min:0',
            "cat_name"=>"required|string",
            "img.*" => "nullable|mimes:jpg,jpeg,png"

        ]);
        if($validate->fails()){
            return $this->ApiResponse(null,422,$validate->errors());
        }

         $validated=$validate->validate();


        $product=Product::find($id);
        if(! $product){
            return $this->ApiResponse(null,404,'product not found in database');
        }

        $updateData = collect($validated)->only(['name','info','price','sale','amount','cat_name'])->toArray();

        if (array_key_exists('cat_name', $validated)) {
            $cat = category::where('name', $validated['cat_name'])->first();
            if(! $cat){

                return $this->ApiResponse(null,404,'category not found in database');
            }

            $updateData['cat_id'] = $cat->id;
        }

        if(! empty($updateData)){
            $updated = $product->update($updateData);
            if(! $updated){
                return $this->Query_fails();
            }
        }

        if($request->hasFile('img')){
            try {
                foreach($request->file('img') as $imgFile){
                    $path=$imgFile->store('image','public');
                    $save_img= img::create([
                        'name'=>$path,
                        'product_id'=>$product->id
                    ]);
                    if(! $save_img){
                        return $this->Query_fails();
                    }
                }
            } catch (\Illuminate\Database\QueryException $e) {
                return $this->ApiResponse(null, 500, 'Image upload failed');
            }
        }

        $product->load('img','category');
        return $this->ApiResponse(new fullProductResource($product),200,'done');
     }

     public function destroy(int $id){
        if(! $id){

            return  $this->Query_fails();
        }else{

            $product=Product::with('img')->find($id);
            if(! $product){
                return $this->ApiResponse(null,404,"product not found");
            }else{
                $product->delete($id);
                if(! $product){
                    return $this->Query_fails();
                }
                foreach($product->img as $img){
                    unlink(storage_path('app/public/'.$img->name));
                    $img->delete();
                }
                return $this->ApiResponse(null,200,"product deleted successfully");
            }

        }

     }
}
