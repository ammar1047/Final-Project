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
        Schema::create('log_nomor_surat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->integer('tahun');
            $table->integer('last_number');
            $table->timestamps();
    
            $table->unique(['kategori_id', 'tahun']);
            $table->foreign('kategori_id')->references('id')->on('kategori_template')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_nomor_surat');
    }
};
