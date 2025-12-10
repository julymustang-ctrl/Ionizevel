<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ionize CMS sayfa tablosu
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id('id_page');
            $table->unsignedBigInteger('id_parent')->default(0);
            $table->unsignedBigInteger('id_menu')->default(0);
            $table->smallInteger('id_type')->default(0);
            $table->unsignedBigInteger('id_subnav')->default(0);
            $table->string('name', 255)->nullable();
            $table->integer('ordering')->default(0);
            $table->integer('level')->default(0);
            $table->boolean('online')->default(false);
            $table->boolean('home')->default(false);
            $table->string('author', 55)->nullable();
            $table->string('updater', 55)->nullable();
            $table->datetime('publish_on')->nullable();
            $table->datetime('publish_off')->nullable();
            $table->datetime('logical_date')->nullable();
            $table->boolean('appears')->default(true);
            $table->boolean('has_url')->default(true);
            $table->string('view', 50)->nullable()->comment('Page view');
            $table->string('view_single', 50)->nullable()->comment('Single Article Page view');
            $table->string('article_list_view', 50)->nullable();
            $table->string('article_view', 50)->nullable();
            $table->string('article_order', 50)->default('ordering');
            $table->string('article_order_direction', 50)->default('ASC');
            $table->string('link', 255)->nullable();
            $table->string('link_type', 25)->nullable();
            $table->string('link_id', 20)->default('');
            $table->boolean('pagination')->default(false);
            $table->tinyInteger('pagination_nb')->default(5);
            $table->tinyInteger('priority')->default(5)->comment('Page priority');
            $table->boolean('used_by_module')->nullable();
            $table->string('deny_code', 3)->nullable();
            $table->timestamps();

            $table->index('id_parent');
            $table->index('level');
            $table->index('id_menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
