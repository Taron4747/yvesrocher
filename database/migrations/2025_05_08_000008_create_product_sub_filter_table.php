<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSubFilterTable extends Migration
{
    public function up()
    {
        Schema::create('product_sub_filter', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_filter_id')->constrained()->onDelete('cascade');
            $table->primary(['product_id', 'sub_filter_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_sub_filter');
    }
}