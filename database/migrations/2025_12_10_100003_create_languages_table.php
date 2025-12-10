<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS dil tablosu
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->string('lang', 3)->primary();
            $table->string('name', 40)->nullable();
            $table->boolean('online')->default(false);
            $table->boolean('def')->default(false)->comment('Is default language');
            $table->integer('ordering')->nullable();
            $table->smallInteger('direction')->default(1)->comment('1: LTR, 2: RTL');
            $table->timestamps();
        });

        // Varsayılan diller
        DB::table('languages')->insert([
            ['lang' => 'tr', 'name' => 'Türkçe', 'online' => true, 'def' => true, 'ordering' => 1, 'direction' => 1],
            ['lang' => 'en', 'name' => 'English', 'online' => true, 'def' => false, 'ordering' => 2, 'direction' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
