<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubFiltersTable extends Migration
{
    public function up()
    {
        Schema::create('sub_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->constrained()->onDelete('cascade');
            $table->string('name_arm');
            $table->string('name_ru');
            $table->string('name_en');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_filters');
    }
}