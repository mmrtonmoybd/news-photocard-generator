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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('title_color')->default('#fffffff');
            $table->boolean('is_date')->default(false);
            $table->date('date')->nullable();
            $table->string('date_color')->default('#000000');
            $table->boolean('is_logo')->default(false);
            $table->string('logo')->nullable();
            $table->string('image');
            $table->string('image_border')->nullable();
            $table->string('footer_short_text')->nullable();
            $table->string('footer_text_color')->default('#000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
