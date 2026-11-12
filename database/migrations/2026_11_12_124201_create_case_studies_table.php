<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('dealership_name');
            $table->string('location')->nullable();
            $table->string('headline');
            $table->string('subheadline')->nullable();
            $table->text('quote')->nullable();
            $table->string('quote_author')->nullable();
            $table->string('quote_author_role')->nullable();
            $table->longText('body')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('metric_label')->nullable();
            $table->string('metric_value')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
