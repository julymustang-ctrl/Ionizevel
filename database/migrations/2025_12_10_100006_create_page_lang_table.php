<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS sayfa dil tablosu (çok dilli içerik)
     */
    public function up(): void
    {
        Schema::create('page_lang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_page');
            $table->string('lang', 3);
            $table->string('url', 100)->default('');
            $table->string('link', 255)->default('');
            $table->string('title', 255)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->string('nav_title', 255)->default('');
            $table->string('subnav_title', 255)->default('');
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->boolean('online')->default(true);
            $table->timestamps();

            $table->primary(['id_page', 'lang']);
            $table->foreign('id_page')->references('id_page')->on('pages')->onDelete('cascade');
            $table->foreign('lang')->references('lang')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_lang');
    }
};
