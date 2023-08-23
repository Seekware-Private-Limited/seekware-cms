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
        Schema::create('career_post_skills', function (Blueprint $table) {
            $table->foreignId('skill_id')->constrained()->on('career_skills')->cascadeOnDelete();
            $table->morphs('career');
            $table->unique(['skill_id', 'career_id', 'career_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_skills');
    }
};
