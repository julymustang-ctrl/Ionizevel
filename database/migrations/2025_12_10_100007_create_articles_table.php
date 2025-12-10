<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS makale tablosu
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id_article');
            $table->string('name', 255)->nullable();
            $table->string('author', 55)->nullable();
            $table->string('updater', 55)->nullable();
            $table->datetime('publish_on')->nullable();
            $table->datetime('publish_off')->nullable();
            $table->datetime('logical_date')->nullable();
            $table->boolean('indexed')->default(false);
            $table->unsignedBigInteger('id_category')->nullable();
            $table->boolean('comment_allow')->default(false);
            $table->boolean('comment_autovalid')->default(false);
            $table->datetime('comment_expire')->nullable();
            $table->smallInteger('flag')->default(0);
            $table->boolean('has_url')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
