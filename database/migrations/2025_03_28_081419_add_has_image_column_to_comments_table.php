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
        Schema::table('Comments', function (Blueprint $table) {
            $table->boolean('hasImage')->nullable(); // When the timer started
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Comments', function (Blueprint $table) {
            $table->dropColumn('hasImage');
        });
    }
};
