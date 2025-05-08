<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFilterTable extends Migration
{
    public function up()
    {
        Schema::create('product_filter', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('filter_id')->constrained()->onDelete('cascade');
            $table->primary(['product_id', 'filter_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_filter');
    }
}