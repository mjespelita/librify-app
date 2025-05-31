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
        Schema::create('onsites', function (Blueprint $table) {
            $table->id();
$table->integer('items_id');
$table->integer('items_types_id');
$table->integer('technicians_id');
$table->integer('sites_id');
$table->integer('quantity');
$table->string('serial_numbers')->nullable();
$table->integer('updated_by');
$table->boolean('isTrash')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onsites');
    }
};
