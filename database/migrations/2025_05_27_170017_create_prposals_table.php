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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('description_arm');
            $table->string('description_ru');
            $table->string('description_en');
            $table->longText('proposition_ru'); 
            $table->longText('proposition_arm'); 
            $table->longText('proposition_en'); 
            $table->string('image'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prposals');
    }
};
