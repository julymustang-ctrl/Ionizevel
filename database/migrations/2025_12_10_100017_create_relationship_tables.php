<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * İlişki tabloları - Sayfa-Makale, Makale-Kategori, Makale-Tag, Sayfa-Medya, Makale-Medya
     */
    public function up(): void
    {
        // Sayfa-Makale ilişkisi
        Schema::create('page_article', function (Blueprint $table) {
            $table->unsignedBigInteger('id_page');
            $table->unsignedBigInteger('id_article');
            $table->boolean('online')->default(false);
            $table->string('view', 50)->nullable();
            $table->integer('ordering')->default(0);
            $table->unsignedBigInteger('id_type')->nullable();
            $table->string('link_type', 25)->default('');
            $table->string('link_id', 20)->default('');
            $table->string('link', 255)->default('');
            $table->boolean('main_parent')->default(false);
            $table->timestamps();

            $table->primary(['id_page', 'id_article']);
            $table->foreign('id_page')->references('id_page')->on('pages')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('articles')->onDelete('cascade');
        });

        // Makale-Kategori ilişkisi
        Schema::create('article_category', function (Blueprint $table) {
            $table->unsignedBigInteger('id_article');
            $table->unsignedBigInteger('id_category');

            $table->primary(['id_article', 'id_category']);
            $table->foreign('id_article')->references('id_article')->on('articles')->onDelete('cascade');
            $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('cascade');
        });

        // Makale-Tag ilişkisi
        Schema::create('article_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('id_article');
            $table->unsignedBigInteger('id_tag');

            $table->primary(['id_article', 'id_tag']);
            $table->foreign('id_article')->references('id_article')->on('articles')->onDelete('cascade');
            $table->foreign('id_tag')->references('id_tag')->on('tags')->onDelete('cascade');
        });

        // Sayfa-Medya ilişkisi
        Schema::create('page_media', function (Blueprint $table) {
            $table->unsignedBigInteger('id_page');
            $table->unsignedBigInteger('id_media');
            $table->boolean('online')->default(true);
            $table->integer('ordering')->default(9999);
            $table->string('lang_display', 3)->nullable();

            $table->primary(['id_page', 'id_media']);
            $table->foreign('id_page')->references('id_page')->on('pages')->onDelete('cascade');
            $table->foreign('id_media')->references('id_media')->on('media')->onDelete('cascade');
        });

        // Makale-Medya ilişkisi
        Schema::create('article_media', function (Blueprint $table) {
            $table->unsignedBigInteger('id_article');
            $table->unsignedBigInteger('id_media');
            $table->boolean('online')->default(true);
            $table->integer('ordering')->default(9999);
            $table->string('url', 255)->nullable();
            $table->string('lang_display', 3)->nullable();

            $table->primary(['id_article', 'id_media']);
            $table->foreign('id_article')->references('id_article')->on('articles')->onDelete('cascade');
            $table->foreign('id_media')->references('id_media')->on('media')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_media');
        Schema::dropIfExists('page_media');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('page_article');
    }
};
