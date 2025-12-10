<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS URL tablosu (SEO-friendly URL'ler)
     */
    public function up(): void
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->id('id_url');
            $table->unsignedBigInteger('id_entity');
            $table->string('type', 10);
            $table->boolean('canonical')->default(false);
            $table->boolean('active')->default(false);
            $table->string('lang', 3);
            $table->string('path', 255)->default('');
            $table->string('path_ids', 50)->nullable();
            $table->string('full_path_ids', 50)->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('active');
            $table->index('lang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
