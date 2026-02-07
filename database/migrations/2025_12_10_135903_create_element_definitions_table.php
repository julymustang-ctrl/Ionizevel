<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Element tanımları - Ionize Content Elements
     * Tekrarlayan alan kümeleri tanımları
     */
    public function up(): void
    {
        Schema::create('element_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // internal name
            $table->string('title'); // display title
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('ordering')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('element_definitions');
    }
};
