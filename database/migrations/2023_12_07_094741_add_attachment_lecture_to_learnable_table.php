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
        Schema::table('learnables', function (Blueprint $table) {
            $table->enum('type', ['public_package', 'private_package', 'category', 'recorded_lecture', 'live_lecture', 'attachment','attachment_lecture'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learnable', function (Blueprint $table) {
            //
        });
    }
};
