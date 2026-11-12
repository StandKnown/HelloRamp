<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('angle')->nullable();
            $table->string('original_path');
            $table->string('enhanced_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('status')->default('pending');
            $table->string('provider')->nullable();
            $table->unsignedInteger('revision')->default(1);
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('reviewed')->default(false);
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
