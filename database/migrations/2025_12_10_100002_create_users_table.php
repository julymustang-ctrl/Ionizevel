<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS user tablosu yapısı
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->unsignedSmallInteger('id_role')->default(4); // User role
            $table->timestamp('join_date')->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->string('username', 50)->unique();
            $table->string('screen_name', 50)->nullable();
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->datetime('birthdate')->nullable();
            $table->smallInteger('gender')->nullable()->comment('1: Male, 2: Female');
            $table->string('password', 255);
            $table->string('email', 120);
            $table->string('salt', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_role')->references('id_role')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
