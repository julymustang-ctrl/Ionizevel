<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS kural tablosu (izin kuralları)
     */
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->unsignedSmallInteger('id_role');
            $table->string('resource', 150);
            $table->string('actions', 150)->default('');
            $table->smallInteger('permission')->nullable();
            $table->unsignedBigInteger('id_element')->nullable();
            $table->timestamps();

            $table->primary(['id_role', 'resource', 'actions']);
            $table->foreign('id_role')->references('id_role')->on('roles')->onDelete('cascade');
        });

        // Super Admin her şeye erişebilir
        DB::table('rules')->insert([
            ['id_role' => 1, 'resource' => 'all', 'actions' => '', 'permission' => 1, 'id_element' => null],
            ['id_role' => 2, 'resource' => 'admin', 'actions' => '', 'permission' => 1, 'id_element' => null],
            ['id_role' => 2, 'resource' => 'admin/page', 'actions' => 'create,edit,delete', 'permission' => 1, 'id_element' => null],
            ['id_role' => 2, 'resource' => 'admin/article', 'actions' => 'create,edit,delete,move,copy,duplicate', 'permission' => 1, 'id_element' => null],
            ['id_role' => 2, 'resource' => 'admin/settings', 'actions' => '', 'permission' => 1, 'id_element' => null],
            ['id_role' => 2, 'resource' => 'admin/user', 'actions' => 'create,edit,delete', 'permission' => 1, 'id_element' => null],
            ['id_role' => 3, 'resource' => 'admin', 'actions' => '', 'permission' => 1, 'id_element' => null],
            ['id_role' => 3, 'resource' => 'admin/page', 'actions' => 'create,edit,delete', 'permission' => 1, 'id_element' => null],
            ['id_role' => 3, 'resource' => 'admin/article', 'actions' => 'create,edit,delete,move,copy,duplicate', 'permission' => 1, 'id_element' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
