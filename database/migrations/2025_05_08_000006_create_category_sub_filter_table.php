<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorySubFilterTable extends Migration
{
    public function up()
    {
        Schema::create('category_sub_filter', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_filter_id')->constrained()->onDelete('cascade');
            $table->primary(['category_id', 'sub_filter_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_sub_filter');
    }
}