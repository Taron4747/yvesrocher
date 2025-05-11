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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(1);
            $table->string('text_arm')->nullable();
            $table->string('text_ru')->nullable();
            $table->string('text_en')->nullable();
            $table->string('link');
            $table->string('image_big')->nullable();
            $table->string('image_medium')->nullable();
            $table->string('image_small')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
