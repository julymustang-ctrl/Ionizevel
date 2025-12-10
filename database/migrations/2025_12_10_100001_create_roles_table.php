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
        Schema::create('roles', function (Blueprint $table) {
            $table->smallIncrements('id_role');
            $table->integer('role_level')->nullable();
            $table->string('role_code', 25)->unique();
            $table->string('role_name', 100);
            $table->text('role_description')->nullable();
            $table->timestamps();
        });

        // Varsayılan roller
        DB::table('roles')->insert([
            ['id_role' => 1, 'role_level' => 10000, 'role_code' => 'super-admin', 'role_name' => 'Super Admin'],
            ['id_role' => 2, 'role_level' => 5000, 'role_code' => 'admin', 'role_name' => 'Admin'],
            ['id_role' => 3, 'role_level' => 1000, 'role_code' => 'editor', 'role_name' => 'Editor'],
            ['id_role' => 4, 'role_level' => 100, 'role_code' => 'user', 'role_name' => 'User'],
            ['id_role' => 5, 'role_level' => 50, 'role_code' => 'pending', 'role_name' => 'Pending'],
            ['id_role' => 6, 'role_level' => 10, 'role_code' => 'guest', 'role_name' => 'Guest'],
            ['id_role' => 7, 'role_level' => -10, 'role_code' => 'banned', 'role_name' => 'Banned'],
            ['id_role' => 8, 'role_level' => -100, 'role_code' => 'deactivated', 'role_name' => 'Deactivated'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
