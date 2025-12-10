<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS makale dil tablosu (çok dilli içerik)
     */
    public function up(): void
    {
        Schema::create('article_lang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_article');
            $table->string('lang', 3);
            $table->string('url', 100)->default('');
            $table->string('title', 255)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->boolean('online')->default(true);
            $table->timestamps();

            $table->primary(['id_article', 'lang']);
            $table->foreign('id_article')->references('id_article')->on('articles')->onDelete('cascade');
            $table->foreign('lang')->references('lang')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_lang');
    }
};
