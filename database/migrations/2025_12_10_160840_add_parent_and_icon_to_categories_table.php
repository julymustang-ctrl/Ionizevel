<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kategorilere parent ve icon alanları ekle
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id_parent')->default(0)->after('id_category');
            $table->string('icon', 50)->nullable()->after('name');
            
            $table->index('id_parent');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['id_parent', 'icon']);
        });
    }
};
