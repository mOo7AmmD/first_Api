<?php

namespace App\Models;

use App\models\Product;
use Illuminate\Database\Eloquent\Model;

class img extends Model
{
    protected $fillable=[
        "name","product_id"
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
