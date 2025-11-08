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
        Schema::create('admins', function (Blueprint $col) {
            $col->id();
            $col->string("name");
            $col->string("email");
            $col->string("password");
            $col->string('age');
            $col->string('is_admin')->default(0);
            $col->enum("gender",["male","female"]);


            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
