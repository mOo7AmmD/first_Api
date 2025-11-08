<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->string('info');
            $col->integer('price');
            $col->integer('sale');
            $col->integer('amount');
            $col->foreignId("cat_id")->constrained('categories')->onDelete('cascade');

            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
