<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS medya tablosu
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id('id_media');
            $table->string('type', 10)->default('');
            $table->string('file_name', 255)->default('');
            $table->string('path', 500)->comment('Complete path to the medium');
            $table->string('base_path', 500)->comment('Medium folder base path');
            $table->string('copyright', 128)->nullable();
            $table->string('provider', 255)->nullable();
            $table->datetime('date')->nullable()->comment('Medium date');
            $table->string('link', 255)->nullable()->comment('Link to a resource');
            $table->enum('square_crop', ['tl', 'm', 'br'])->default('m');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
