<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS menü tablosu
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('name', 50)->unique();
            $table->string('title', 50);
            $table->integer('ordering')->nullable();
            $table->timestamps();
        });

        // Varsayılan menüler
        DB::table('menus')->insert([
            ['id_menu' => 1, 'name' => 'main', 'title' => 'Main menu', 'ordering' => 1],
            ['id_menu' => 2, 'name' => 'system', 'title' => 'System menu', 'ordering' => 2],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
