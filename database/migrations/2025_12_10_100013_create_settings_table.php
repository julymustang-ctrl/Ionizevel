<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS ayarlar tablosu
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id('id_setting');
            $table->string('name', 255);
            $table->text('content');
            $table->string('lang', 3)->nullable();
            $table->timestamps();

            $table->index('name');
        });

        // Varsayılan ayarlar
        DB::table('settings')->insert([
            ['name' => 'website_email', 'content' => '', 'lang' => null],
            ['name' => 'files_path', 'content' => 'files', 'lang' => null],
            ['name' => 'cache', 'content' => '0', 'lang' => null],
            ['name' => 'cache_time', 'content' => '150', 'lang' => null],
            ['name' => 'theme', 'content' => 'default', 'lang' => null],
            ['name' => 'theme_admin', 'content' => 'admin', 'lang' => null],
            ['name' => 'texteditor', 'content' => 'tinymce', 'lang' => null],
            ['name' => 'media_thumb_size', 'content' => '120', 'lang' => null],
            ['name' => 'default_admin_lang', 'content' => 'tr', 'lang' => null],
            ['name' => 'ionize_version', 'content' => '1.0.0', 'lang' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
