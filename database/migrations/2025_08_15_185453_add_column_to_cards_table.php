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
        Schema::table('cards', function (Blueprint $table) {
            $table->text('title')->change();

            $table->unsignedBigInteger('logo_id')->nullable()->after('is_logo');
            $table->foreign('logo_id')->references('id')->on('logos')->onDelete('set null');

            $table->unsignedBigInteger('footer_text_id')->nullable()->after('logo_id');
            $table->foreign('footer_text_id')->references('id')->on('footer_texts')->onDelete('set null');

            $table->string('hash')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
