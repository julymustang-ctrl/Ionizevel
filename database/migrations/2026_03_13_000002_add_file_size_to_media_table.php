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
        Schema::table('media', function (Blueprint $table) {
            $table->integer('file_size')->nullable()->after('path')->comment('File size in bytes');
            $table->integer('width')->nullable()->after('file_size')->comment('Image width');
            $table->integer('height')->nullable()->after('width')->comment('Image height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['file_size', 'width', 'height']);
        });
    }
};
