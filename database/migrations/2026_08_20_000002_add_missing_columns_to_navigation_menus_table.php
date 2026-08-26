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
        Schema::table('navigation_menus', function (Blueprint $table) {
            if (!Schema::hasColumn('navigation_menus', 'image_url')) {
                $table->string('image_url')->nullable()->after('target');
            }
            if (!Schema::hasColumn('navigation_menus', 'pdf_url')) {
                $table->string('pdf_url')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('navigation_menus', 'description')) {
                $table->text('description')->nullable()->after('pdf_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            if (Schema::hasColumn('navigation_menus', 'image_url')) {
                $table->dropColumn('image_url');
            }
            if (Schema::hasColumn('navigation_menus', 'pdf_url')) {
                $table->dropColumn('pdf_url');
            }
            if (Schema::hasColumn('navigation_menus', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
