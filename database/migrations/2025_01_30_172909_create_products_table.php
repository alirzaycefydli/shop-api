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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->string('slug')->unique();
            $table->longText('description');
            $table->longText('short_description');
            $table->string('brand');
            $table->integer('price')->nullable(false);
            $table->integer('quantity')->default(0);
            $table->integer('discount_percent')->default(0);
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
