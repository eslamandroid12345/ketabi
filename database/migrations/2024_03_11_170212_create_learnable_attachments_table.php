<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('learnable_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('path')->nullable();
            $table->foreignId('learnable_id')->nullable()->constrained('learnables')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learnable_attachments');
    }
};
