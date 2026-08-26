<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('org_charts')->onDelete('cascade');
            $table->string('title'); // Nama Jabatan (misal: Kepala Dinas)
            $table->string('name'); // Nama Pejabat (misal: Ir. H. Ahmad, M.Si)
            $table->string('nip')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('line_type', ['command', 'coordination'])->default('command'); // Garis Komando / Garis Koordinasi
            $table->integer('order_no')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_charts');
    }
};
