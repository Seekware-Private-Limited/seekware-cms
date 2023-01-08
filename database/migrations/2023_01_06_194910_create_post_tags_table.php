<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_tags', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->on('blog_tags')->cascadeOnDelete();
            $table->morphs('blog');
            $table->unique(['tag_id', 'blog_id', 'blog_type']);
            //$table->foreignId('blog_id')->constrained()->on('blog_posts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_tags');
    }
};
