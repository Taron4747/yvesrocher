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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('name_en');
            $table->string('name_ru');
            $table->string('name_arm');
            $table->string('description_en');
            $table->string('description_ru');
            $table->string('description_arm');
            $table->string('composition_en');
            $table->string('composition_ru');
            $table->string('composition_arm');
            $table->string('image');
            $table->integer('price');
            $table->integer('size');
            $table->integer('is_exist')->default(1);
            $table->integer('is_new')->default(1);
            $table->integer('is_bestseller')->default(0);
            $table->integer('discount')->nullable();
            $table->timestamps();
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
