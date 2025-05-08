<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryFilterTable extends Migration
{
    public function up()
    {
        Schema::create('category_filter', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('filter_id')->constrained()->onDelete('cascade');
            $table->primary(['category_id', 'filter_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_filter');
    }
}