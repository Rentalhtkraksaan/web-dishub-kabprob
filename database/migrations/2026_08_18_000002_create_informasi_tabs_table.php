<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_tabs', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Nama tab, misal: Berita, Foto, Video
            $table->string('slug')->unique(); // berita, foto, video
            $table->string('icon')->nullable()->default('fas fa-newspaper'); // FontAwesome icon
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('filter_type')->default('all');   // 'all' | 'category' | 'type'
            $table->string('filter_value')->nullable();       // misal 'Foto' jika filter_type='category'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_tabs');
    }
};
