<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('public_documents', 'type')) {
                $table->string('type')->default('perencanaan-kinerja')->after('title');
            }
            if (!Schema::hasColumn('public_documents', 'tahun')) {
                $table->string('tahun', 10)->nullable()->after('category');
            }
            if (!Schema::hasColumn('public_documents', 'file_zip_path')) {
                $table->string('file_zip_path')->nullable()->after('file_url');
            }
            if (!Schema::hasColumn('public_documents', 'file_zip_url')) {
                $table->string('file_zip_url')->nullable()->after('file_zip_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('public_documents', function (Blueprint $table) {
            if (Schema::hasColumn('public_documents', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('public_documents', 'tahun')) {
                $table->dropColumn('tahun');
            }
            if (Schema::hasColumn('public_documents', 'file_zip_path')) {
                $table->dropColumn('file_zip_path');
            }
            if (Schema::hasColumn('public_documents', 'file_zip_url')) {
                $table->dropColumn('file_zip_url');
            }
        });
    }
};
