<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS kaynak tablosu (izin sistemi)
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id('id_resource');
            $table->unsignedBigInteger('id_parent')->nullable();
            $table->string('resource', 255)->unique();
            $table->string('actions', 500)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('description', 1000)->nullable();
            $table->timestamps();
        });

        // Temel kaynaklar
        DB::table('resources')->insert([
            ['id_resource' => 1, 'id_parent' => null, 'resource' => 'admin', 'actions' => '', 'title' => 'Backend login', 'description' => 'Connect to ionize backend'],
            ['id_resource' => 10, 'id_parent' => null, 'resource' => 'admin/menu', 'actions' => 'create,edit,delete', 'title' => 'Menu', 'description' => 'Menus'],
            ['id_resource' => 40, 'id_parent' => null, 'resource' => 'admin/page', 'actions' => 'create,edit,delete', 'title' => 'Page', 'description' => 'Page'],
            ['id_resource' => 70, 'id_parent' => null, 'resource' => 'admin/article', 'actions' => 'create,edit,delete,move,copy,duplicate', 'title' => 'Article', 'description' => 'Article'],
            ['id_resource' => 270, 'id_parent' => null, 'resource' => 'admin/settings', 'actions' => '', 'title' => 'Settings', 'description' => 'Settings'],
            ['id_resource' => 300, 'id_parent' => null, 'resource' => 'admin/users_roles', 'actions' => '', 'title' => 'Users / Roles', 'description' => 'Users / Roles'],
            ['id_resource' => 301, 'id_parent' => 300, 'resource' => 'admin/user', 'actions' => 'create,edit,delete', 'title' => 'Users', 'description' => 'Users'],
            ['id_resource' => 302, 'id_parent' => 300, 'resource' => 'admin/role', 'actions' => 'create,edit,delete', 'title' => 'Roles', 'description' => 'Roles'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
