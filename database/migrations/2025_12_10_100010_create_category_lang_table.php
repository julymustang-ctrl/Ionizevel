<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS kategori dil tablosu
     */
    public function up(): void
    {
        Schema::create('category_lang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_category');
            $table->string('lang', 3);
            $table->string('title', 255)->default('');
            $table->string('subtitle', 255)->default('');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->primary(['id_category', 'lang']);
            $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('cascade');
            $table->foreign('lang')->references('lang')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_lang');
    }
};
