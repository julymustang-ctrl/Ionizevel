<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sayfa erişim kontrol listesi (ACL)
     * Ionize'daki ion_page_acl benzeri
     */
    public function up(): void
    {
        Schema::create('page_acl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_page');
            $table->unsignedBigInteger('id_role');
            $table->enum('access_type', ['allow', 'deny'])->default('allow');
            $table->timestamps();

            $table->unique(['id_page', 'id_role']);
            $table->index('id_page');
            $table->index('id_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_acl');
    }
};
