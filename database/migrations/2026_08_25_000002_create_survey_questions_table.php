<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('step_number')->unique();
            $table->string('category')->nullable();
            $table->text('question');
            $table->timestamps();
        });

        // Insert default 10 SKM questions
        DB::table('survey_questions')->insert([
            [
                'step_number' => 1,
                'category'    => 'Persyaratan',
                'question'    => 'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 2,
                'category'    => 'Prosedur',
                'question'    => 'Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan di dinas perhubungan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 3,
                'category'    => 'Waktu Pelayanan',
                'question'    => 'Bagaimana pendapat Saudara tentang kecepatan waktu dalam memberikan pelayanan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 4,
                'category'    => 'Biaya / Tarif',
                'question'    => 'Bagaimana pendapat Saudara tentang kewajaran biaya/tarif dalam pelayanan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 5,
                'category'    => 'Kompetensi Petugas',
                'question'    => 'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam pelayanan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 6,
                'category'    => 'Perilaku & Kesopanan',
                'question'    => 'Bagaimana pendapat Saudara tentang perilaku petugas terkait kesopanan dan keramahan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 7,
                'category'    => 'Sarana Prasarana',
                'question'    => 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana pelayanan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 8,
                'category'    => 'Penanganan Pengaduan',
                'question'    => 'Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 9,
                'category'    => 'Kepuasan Keseluruhan',
                'question'    => 'Secara keseluruhan, bagaimana tingkat kepuasan Anda terhadap pelayanan DISHUB?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'step_number' => 10,
                'category'    => 'Saran & Masukan',
                'question'    => 'Ceritain dong, menurutmu apa yang perlu kita perbaiki atau tingkatkan dari pelayanan DISHUB Kabupaten Probolinggo?',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
