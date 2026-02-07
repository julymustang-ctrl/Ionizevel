<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sayfa/Makale element verileri - gerçek content element değerleri
     */
    public function up(): void
    {
        Schema::create('page_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_definition_id')->constrained()->onDelete('cascade');
            $table->string('parent_type'); // page, article
            $table->unsignedBigInteger('parent_id'); // page.id_page or article.id_article
            $table->string('lang', 5)->nullable(); // for translatable fields
            $table->integer('ordering')->default(0); // for repeatable elements
            $table->json('data'); // all field values as JSON
            $table->timestamps();

            $table->index(['parent_type', 'parent_id']);
            $table->index(['element_definition_id', 'parent_type', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_elements');
    }
};
