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
        Schema::table('banners', function (Blueprint $table) {
            $table->longText('text_arm')->change(); // Изменяем тип столбца
            $table->longText('text_ru')->change(); // Изменяем тип столбца
            $table->longText('text_en')->change(); // Изменяем тип столбца
            $table->longText('proposition_ru')->nullable()->change(); // Изменяем тип столбца
            $table->longText('proposition_arm')->nullable()->change(); // Изменяем тип столбца
            $table->longText('proposition_en')->nullable()->change(); // Изменяем тип столбца

        });
        Schema::table('products', function (Blueprint $table) {
            $table->longText('description_arm')->change(); // Изменяем тип столбца
            $table->longText('description_ru')->change(); // Изменяем тип столбца
            $table->longText('description_en')->change(); // Изменяем тип столбца
          

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
};
