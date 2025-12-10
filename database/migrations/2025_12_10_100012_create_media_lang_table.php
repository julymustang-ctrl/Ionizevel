<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS medya dil tablosu
     */
    public function up(): void
    {
        Schema::create('media_lang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_media');
            $table->string('lang', 3);
            $table->string('title', 255)->nullable();
            $table->string('alt', 500)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->primary(['id_media', 'lang']);
            $table->foreign('id_media')->references('id_media')->on('media')->onDelete('cascade');
            $table->foreign('lang')->references('lang')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_lang');
    }
};
