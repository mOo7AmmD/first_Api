<?php

namespace App\Models;
use App\Models\category;
use App\Models\img;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=[
        "name","info",'price','sale','amount','cat_id'

    ];

    public function category(){
        return $this->belongsTo(category::class,'cat_id');
    }

    public function img(){
        return $this->hasMany(img::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function userInCart( ){
        return $this->belongsToMany(User::class,'charts')
        ->withPivot('quantity')
        ->withTimestamps();

    }
    public function favorites(){
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id')
        ->withTimestamps();
    }
}
