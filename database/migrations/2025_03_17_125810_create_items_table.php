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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
$table->string('itemId');
$table->string('name');
$table->string('model');
$table->string('brand');
$table->string('types_id');
$table->longtext('description');
$table->string('quantity');
$table->string('serial_numbers')->nullable();
$table->string('unit');
$table->boolean('isTrash')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
