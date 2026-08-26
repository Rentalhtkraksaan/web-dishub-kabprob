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
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'image_url')) {
                $table->string('image_url')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('pages', 'pdf_url')) {
                $table->string('pdf_url')->nullable()->after('image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'image_url')) {
                $table->dropColumn('image_url');
            }
            if (Schema::hasColumn('pages', 'pdf_url')) {
                $table->dropColumn('pdf_url');
            }
        });
    }
};
