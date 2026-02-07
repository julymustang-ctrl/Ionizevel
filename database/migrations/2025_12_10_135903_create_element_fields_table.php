<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Element alanları - her element tanımının içerdiği alanlar
     */
    public function up(): void
    {
        Schema::create('element_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_definition_id')->constrained()->onDelete('cascade');
            $table->string('name'); // internal name
            $table->string('label'); // display label
            $table->string('type')->default('text'); // text, textarea, wysiwyg, image, select, checkbox, number, date
            $table->text('options')->nullable(); // JSON for select options, validation rules, etc.
            $table->text('default_value')->nullable();
            $table->boolean('translatable')->default(false);
            $table->boolean('required')->default(false);
            $table->integer('ordering')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('element_fields');
    }
};
