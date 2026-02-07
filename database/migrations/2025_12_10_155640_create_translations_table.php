<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Arayüz çevirileri tablosu
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general'); // validation, auth, messages, etc.
            $table->string('key'); // e.g., "welcome_message"
            $table->string('lang', 5);
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['group', 'key', 'lang']);
            $table->index(['group', 'lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
