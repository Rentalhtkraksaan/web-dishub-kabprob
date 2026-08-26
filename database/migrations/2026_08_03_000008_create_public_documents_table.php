<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('perencanaan-kinerja'); // perencanaan-kinerja, pengukuran-kinerja, pelaporan-kinerja, evaluasi-kinerja
            $table->string('category')->default('Rencana Strategis'); 
            $table->string('tahun', 4)->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_zip_path')->nullable();
            $table->string('file_zip_url')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_documents');
    }
};
