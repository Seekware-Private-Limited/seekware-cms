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
        Schema::create('career_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained()->on('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('job_type');
            $table->string('exp_level');
            $table->longText('responsibilities')->nullable();
            $table->longText('skill_desc')->nullable();
            $table->string('experience');
            $table->string('salary');
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->longText('description');
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
