@extends('public.layouts.app')

@section('title', 'Survei Kepuasan Masyarakat (SKM) | DISHUB Kabupaten Probolinggo')

@section('content')
<div class="py-5" style="background: linear-gradient(180deg, #07172f 0%, #0f2b5c 50%, #0a1f3d 100%); min-height: 88vh; font-family: 'Outfit', sans-serif;">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">

                <!-- MAIN SURVEI CARD (SUKMA-E STYLE DISHUB) -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 position-relative" id="surveiCard" style="border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);">
                    
                    <!-- HEADER LOGO BANNER -->
                    <div class="p-4 p-md-5 border-bottom d-flex align-items-center justify-content-between" style="border-color: #f1f5f9 !important; background: #ffffff;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2.5 rounded-2xl bg-primary text-white d-flex align-items-center justify-content-center shadow-md" style="width: 52px; height: 52px; background: linear-gradient(135deg, #0f2b5c 0%, #1e40af 100%) !important; border-radius: 16px;">
                                <i class="fas fa-clipboard-check text-2xl text-warning"></i>
                            </div>
                            <div>
                                <h3 class="font-weight-black mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.3px; color: #0f172a !important;">Sukma-e <span class="badge badge-warning text-dark font-weight-extrabold text-xs ml-1 px-2 py-0.5" style="border-radius: 6px;">DISHUB</span></h3>
                                <p class="text-muted small mb-0 font-weight-bold" style="font-size: 0.78rem; color: #64748b !important;">Survei Kepuasan Masyarakat Elektronik</p>
                            </div>
                        </div>
                        <span class="badge badge-light border text-slate-600 px-3 py-1.5 font-weight-bold d-none d-sm-inline-block" style="border-radius: 30px; font-size: 0.75rem;">
                            <i class="fas fa-shield-alt text-success mr-1"></i> Resmi & Terlindungi
                        </span>
                    </div>

                    <!-- STEP FORM CONTAINER -->
                    <div class="p-4 p-md-5">

                        <!-- PROGRESS BAR -->
                        <div id="progressContainer" class="mb-4" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-1 text-xs font-weight-bold">
                                <span class="text-primary font-weight-extrabold" id="stepCounterBadge" style="color: #0f2b5c !important;">Soal 1 / 10</span>
                                <span class="text-muted" id="percentBadge">10% Selesai</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 10px; background: #e2e8f0;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" style="width: 10%; background: linear-gradient(90deg, #0f2b5c 0%, #2563eb 100%);" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <form id="surveiForm">
                            @csrf
                            
                            <!-- STEP 0: DATA DIRI RESPONDEN -->
                            <div class="survei-step active-step" id="step-0">
                                
                                <!-- PUBLIC IKM SUMMARY BANNER -->
                                <div class="mb-4 p-4 rounded-2xl text-white shadow-sm" style="background: linear-gradient(135deg, #0f2b5c 0%, #1e3a8a 100%); border-radius: 20px;">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                        <div>
                                            <span class="badge badge-warning text-dark font-weight-extrabold text-xs px-2.5 py-1 mb-2" style="border-radius: 8px;">
                                                <i class="fas fa-chart-line mr-1"></i> IKM RESMI DISHUB
                                            </span>
                                            <h5 class="font-weight-black text-white mb-1" style="font-size: 1.15rem;">Hasil Survei Kepuasan Masyarakat</h5>
                                            <p class="text-white-50 text-xs mb-0">Indeks Mutu Pelayanan Publik Dinas Perhubungan Kabupaten Probolinggo</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                            <div class="bg-white-10 text-center px-3 py-2 rounded-xl border border-white-20" style="background: rgba(255,255,255,0.12); border-radius: 14px;">
                                                <small class="d-block text-white-50 font-weight-bold" style="font-size: 0.65rem;">MUTU PELAYANAN</small>
                                                <span class="font-weight-black text-warning" style="font-size: 0.95rem;">{{ $mutu ?? 'A (Sangat Baik)' }}</span>
                                            </div>
                                            <div class="bg-warning text-dark text-center px-3.5 py-2 rounded-xl font-weight-bold shadow" style="border-radius: 14px;">
                                                <small class="d-block text-dark-50 font-weight-extrabold" style="font-size: 0.65rem;">SKOR IKM</small>
                                                <span class="font-weight-black" style="font-size: 1.05rem;">{{ number_format($avgScore ?? 4.0, 2) }} / 4.0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h4 class="font-weight-black text-dark mb-1" style="font-size: 1.25rem; color: #0f172a !important;">Form Data Diri Responden</h4>
                                    <p class="text-muted small mb-0" style="color: #64748b !important;">Dinas Perhubungan Kabupaten Probolinggo - Silakan isi data diri Anda untuk memulai survei.</p>
                                </div>

                                <div class="form-group mb-3 position-relative">
                                    <label class="font-weight-extrabold text-dark small mb-1">Nama Responden <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;"><i class="far fa-user text-muted"></i></span>
                                        </div>
                                        <input type="text" name="name" id="name" required placeholder="Masukkan Nama Responden" class="form-control bg-light border-left-0 font-weight-medium" style="border-radius: 0 12px 12px 0; height: 48px; font-size: 0.9rem;">
                                    </div>
                                </div>

                                <div class="form-group mb-3 position-relative">
                                    <label class="font-weight-extrabold text-dark small mb-1">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-mobile-alt text-muted"></i></span>
                                        </div>
                                        <input type="tel" name="phone" id="phone" required placeholder="Masukkan nomor telepon (08xxxxxxxxxx)" class="form-control bg-light border-left-0 font-weight-medium" style="border-radius: 0 12px 12px 0; height: 48px; font-size: 0.9rem;">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 form-group mb-3">
                                        <label class="font-weight-extrabold text-dark small mb-1">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" required class="form-control bg-light font-weight-medium custom-select" style="border-radius: 12px; height: 48px; font-size: 0.9rem;">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 form-group mb-4">
                                        <label class="font-weight-extrabold text-dark small mb-1">Umur Anda (Tahun) <span class="text-danger">*</span></label>
                                        <input type="number" name="age" id="age" min="17" max="120" required placeholder="Contoh: 28" class="form-control bg-light font-weight-medium" style="border-radius: 12px; height: 48px; font-size: 0.9rem;">
                                    </div>
                                </div>

                                <div class="pt-2 text-right">
                                    <button type="button" onclick="startSurvei()" class="btn text-white font-weight-extrabold px-5 py-3 shadow-lg hover-scale" style="background: linear-gradient(135deg, #0052d4 0%, #4364f7 50%, #6fb1fc 100%); border-radius: 14px; font-size: 0.95rem; border: none;">
                                        Mulai Isi Survei <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 1 - 9: SOAL PILIHAN GANDA (1/10 s/d 9/10) -->
                            @php
                                $qMap = isset($questions) ? $questions : collect();
                            @endphp
                            
                            <!-- SOAL 1 (1/10) -->
                            <div class="survei-step d-none" id="step-1">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    1. {{ $qMap->has(1) ? $qMap->get(1)->question : 'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q1', 'Sangat sesuai.', this)">
                                        <input type="radio" name="q1" value="Sangat sesuai.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q1', 'Sesuai.', this)">
                                        <input type="radio" name="q1" value="Sesuai.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q1', 'Kurang sesuai.', this)">
                                        <input type="radio" name="q1" value="Kurang sesuai.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q1', 'Tidak sesuai.', this)">
                                        <input type="radio" name="q1" value="Tidak sesuai.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak sesuai.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 2 (2/10) -->
                            <div class="survei-step d-none" id="step-2">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    2. {{ $qMap->has(2) ? $qMap->get(2)->question : 'Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan di dinas perhubungan?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q2', 'Sangat mudah.', this)">
                                        <input type="radio" name="q2" value="Sangat mudah.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat mudah.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q2', 'Mudah.', this)">
                                        <input type="radio" name="q2" value="Mudah.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Mudah.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q2', 'Kurang mudah.', this)">
                                        <input type="radio" name="q2" value="Kurang mudah.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang mudah.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q2', 'Tidak mudah.', this)">
                                        <input type="radio" name="q2" value="Tidak mudah.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak mudah.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 3 (3/10) -->
                            <div class="survei-step d-none" id="step-3">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    3. {{ $qMap->has(3) ? $qMap->get(3)->question : 'Bagaimana pendapat Saudara tentang kecepatan waktu dalam memberikan pelayanan?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q3', 'Sangat cepat.', this)">
                                        <input type="radio" name="q3" value="Sangat cepat.">
                                        <span class="emoji-icon">⚡</span>
                                        <span class="option-text">Sangat cepat.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q3', 'Cepat.', this)">
                                        <input type="radio" name="q3" value="Cepat.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Cepat.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q3', 'Kurang cepat.', this)">
                                        <input type="radio" name="q3" value="Kurang cepat.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang cepat.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q3', 'Tidak cepat.', this)">
                                        <input type="radio" name="q3" value="Tidak cepat.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak cepat.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 4 (4/10) -->
                            <div class="survei-step d-none" id="step-4">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    4. {{ $qMap->has(4) ? $qMap->get(4)->question : 'Bagaimana pendapat Saudara tentang kesesuaian biaya pelayanan?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q4', 'Sangat sesuai.', this)">
                                        <input type="radio" name="q4" value="Sangat sesuai.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q4', 'Sesuai.', this)">
                                        <input type="radio" name="q4" value="Sesuai.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q4', 'Kurang sesuai.', this)">
                                        <input type="radio" name="q4" value="Kurang sesuai.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q4', 'Tidak sesuai.', this)">
                                        <input type="radio" name="q4" value="Tidak sesuai.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak sesuai.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 5 (5/10) -->
                            <div class="survei-step d-none" id="step-5">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    5. {{ $qMap->has(5) ? $qMap->get(5)->question : 'Bagaimana pendapat Saudara tentang kesesuaian produk pelayanan?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q5', 'Sangat sesuai.', this)">
                                        <input type="radio" name="q5" value="Sangat sesuai.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q5', 'Sesuai.', this)">
                                        <input type="radio" name="q5" value="Sesuai.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q5', 'Kurang sesuai.', this)">
                                        <input type="radio" name="q5" value="Kurang sesuai.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang sesuai.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q5', 'Tidak sesuai.', this)">
                                        <input type="radio" name="q5" value="Tidak sesuai.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak sesuai.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 6 (6/10) -->
                            <div class="survei-step d-none" id="step-6">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    6. {{ $qMap->has(6) ? $qMap->get(6)->question : 'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q6', 'Sangat kompeten.', this)">
                                        <input type="radio" name="q6" value="Sangat kompeten.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat kompeten.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q6', 'Kompeten.', this)">
                                        <input type="radio" name="q6" value="Kompeten.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Kompeten.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q6', 'Kurang kompeten.', this)">
                                        <input type="radio" name="q6" value="Kurang kompeten.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang kompeten.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q6', 'Tidak kompeten.', this)">
                                        <input type="radio" name="q6" value="Tidak kompeten.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak kompeten.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 7 (7/10) -->
                            <div class="survei-step d-none" id="step-7">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    7. {{ $qMap->has(7) ? $qMap->get(7)->question : 'Bagaimana pendapat Saudara tentang perilaku petugas terkait kesopanan dan keramahan?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q7', 'Sangat sopan dan ramah.', this)">
                                        <input type="radio" name="q7" value="Sangat sopan dan ramah.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat sopan dan ramah.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q7', 'Sopan dan ramah.', this)">
                                        <input type="radio" name="q7" value="Sopan dan ramah.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Sopan dan ramah.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q7', 'Kurang sopan dan ramah.', this)">
                                        <input type="radio" name="q7" value="Kurang sopan dan ramah.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang sopan dan ramah.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q7', 'Tidak sopan dan ramah.', this)">
                                        <input type="radio" name="q7" value="Tidak sopan dan ramah.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak sopan dan ramah.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 8 (8/10) -->
                            <div class="survei-step d-none" id="step-8">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    8. {{ $qMap->has(8) ? $qMap->get(8)->question : 'Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q8', 'Sangat baik.', this)">
                                        <input type="radio" name="q8" value="Sangat baik.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat baik.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q8', 'Baik.', this)">
                                        <input type="radio" name="q8" value="Baik.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Baik.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q8', 'Kurang baik.', this)">
                                        <input type="radio" name="q8" value="Kurang baik.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang baik.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q8', 'Tidak baik.', this)">
                                        <input type="radio" name="q8" value="Tidak baik.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak baik.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SOAL 9 (9/10) -->
                            <div class="survei-step d-none" id="step-9">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">SKM DISHUB Kabupaten Probolinggo</p>
                                <h4 class="font-weight-black text-dark mb-4" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    9. {{ $qMap->has(9) ? $qMap->get(9)->question : 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana?' }}
                                </h4>
                                <div class="option-group space-y-3">
                                    <label class="option-card" onclick="selectOption('q9', 'Sangat berkualitas.', this)">
                                        <input type="radio" name="q9" value="Sangat berkualitas.">
                                        <span class="emoji-icon">😻</span>
                                        <span class="option-text">Sangat berkualitas.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q9', 'Berkualitas.', this)">
                                        <input type="radio" name="q9" value="Berkualitas.">
                                        <span class="emoji-icon">😊</span>
                                        <span class="option-text">Berkualitas.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q9', 'Kurang berkualitas.', this)">
                                        <input type="radio" name="q9" value="Kurang berkualitas.">
                                        <span class="emoji-icon">😫</span>
                                        <span class="option-text">Kurang berkualitas.</span>
                                    </label>
                                    <label class="option-card" onclick="selectOption('q9', 'Tidak berkualitas.', this)">
                                        <input type="radio" name="q9" value="Tidak berkualitas.">
                                        <span class="emoji-icon">😡</span>
                                        <span class="option-text">Tidak berkualitas.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- STEP 10: SARAN DAN MASUKAN BEBAS -->
                            <div class="survei-step d-none" id="step-10">
                                <p class="text-muted font-weight-bold small mb-1" style="color: #64748b !important;">Langkah Terakhir</p>
                                <h4 class="font-weight-black text-dark mb-3" style="font-size: 1.15rem; line-height: 1.45; color: #0f172a !important;">
                                    Tuliskan Saran & Masukan Anda untuk Kami (Opsional)
                                </h4>
                                <div class="form-group mb-4">
                                    <textarea name="feedback" id="feedback" rows="5" placeholder="Tuliskan saran, kritik, atau masukan Anda secara jujur demi kemajuan pelayanan Dinas Perhubungan..." class="form-control bg-light p-3 font-weight-medium" style="border-radius: 16px; font-size: 0.95rem; border: 1px solid #cbd5e1;"></textarea>
                                </div>
                            </div>

                            <!-- NAVIGATION BUTTONS (KIRIM JAWABAN SAAT DI SOAL TERAKHIR) -->
                            <div class="pt-4 border-top text-right" id="navButtons" style="display: none !important;">
                                <button type="button" id="submitModalBtn" onclick="openCaptchaModal()" class="btn text-white font-weight-extrabold px-5 py-2.5 shadow-lg" style="background: #16a34a; border-radius: 12px; font-size: 0.95rem; border: none;">
                                    <i class="fas fa-check-circle mr-1.5"></i> Kirim Jawaban Survei
                                </button>
                            </div>

                        </form>

                        <!-- SUCCESS SCREEN -->
                        <div id="successScreen" class="text-center py-5 d-none">
                            <div class="mb-4">
                                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full d-inline-flex align-items-center justify-content-center mx-auto shadow-inner" style="width: 85px; height: 85px; background: #d1fae5; color: #059669; border-radius: 50%;">
                                    <i class="fas fa-check-circle text-5xl"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-black text-dark mb-2" style="font-size: 1.6rem;">Terima Kasih Banyak!</h3>
                            <p class="text-muted max-w-md mx-auto mb-4" style="font-size: 0.95rem;">
                                Jawaban & Masukan Survei Kepuasan Masyarakat (SKM) Anda telah berhasil tersimpan secara resmi.
                            </p>
                            <a href="{{ route('home') }}" class="btn text-white font-weight-extrabold px-5 py-3 shadow-md" style="background: #0f2b5c; border-radius: 14px; font-size: 0.9rem;">
                                <i class="fas fa-home mr-2"></i> Kembali ke Beranda DISHUB
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>

        <!-- FOOTER BRANDING -->
                <div class="text-center mt-4 text-white-50 small font-weight-bold">
                    © 2026 - Sukma-e DISHUB | Pemerintah Kabupaten Probolinggo
                </div>

            </div>
        </div>
    </div>
</div>

<!-- CAPTCHA VERIFICATION MODAL (EXACT SUKMA-E STYLE) -->
<div class="modal fade" id="captchaModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3xl border-0 shadow-2xl overflow-hidden" style="border-radius: 24px;">
            <div class="modal-header px-4 py-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-black text-dark text-base mb-0" style="font-size: 0.98rem; color: #0f172a;">
                    <i class="fas fa-shield-alt text-primary mr-1.5"></i> Konfirmasi dulu dong, sebelum lanjut !!
                </h5>
                <button type="button" class="close font-weight-bold text-muted" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                
                <!-- Captcha Image Display Box -->
                <div class="p-3 bg-slate-50 border rounded-2xl mb-3 d-inline-block position-relative shadow-xs" style="background: #f8fafc; border-radius: 16px; min-width: 200px;">
                    <span id="captchaDisplay" class="font-weight-black tracking-widest text-dark" style="font-size: 2.2rem; font-family: 'Courier New', monospace; letter-spacing: 12px !important; text-decoration: line-through; background: #e2e8f0; padding: 4px 16px; border-radius: 8px; color: #1e293b !important; display: inline-block;">
                        8226
                    </span>
                    <button type="button" onclick="refreshCaptchaCode()" class="btn btn-sm btn-circle btn-light border ml-2 shadow-xs" title="Refresh Captcha" style="border-radius: 50%; width: 36px; height: 36px;">
                        <i class="fas fa-sync-alt text-primary"></i>
                    </button>
                </div>

                <div class="form-group text-left mb-3 position-relative">
                    <input type="text" id="captchaInput" autocomplete="off" class="form-control text-center font-weight-extrabold text-uppercase bg-white" placeholder="Tulis ulang nomor diatas" style="border-radius: 12px; height: 50px; font-size: 1.15rem; letter-spacing: 4px; pointer-events: auto !important; position: relative; z-index: 1080; border: 2px solid #cbd5e1;">
                    <p class="text-muted text-xs mt-2 text-center" style="font-size: 0.75rem; color: #94a3b8 !important;">
                        Kami menggunakan captcha untuk keamanan data serta memastikan data yang dimasukkan bukan berasal dari robot.
                    </p>
                </div>

                <div id="captchaError" class="alert alert-danger font-weight-bold small py-2 d-none" style="border-radius: 10px;"></div>

                <button type="button" id="finalSubmitBtn" onclick="submitFinalForm()" class="btn text-white font-weight-extrabold w-100 py-3 shadow-md" style="background: #007bff; border-radius: 14px; font-size: 0.95rem; border: none;">
                    Lanjut kirim jawaban <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* OPTION CARD STYLING WITH SMOOTH ANIMATIONS & HOVER GLOW */
.option-card {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    user-select: none;
    margin-bottom: 12px;
}

.option-card:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.12);
}

.option-card.selected-card {
    border-color: #2563eb;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.18);
}

.option-card input[type="radio"] {
    display: none;
}

.emoji-icon {
    font-size: 1.6rem;
    margin-right: 14px;
    transition: transform 0.25s ease;
}

.option-card:hover .emoji-icon {
    transform: scale(1.2);
}

.option-text {
    font-size: 0.98rem;
    font-weight: 700;
    color: #1e293b;
}

/* STEP FADE ANIMATION */
.survei-step {
    animation: fadeInStep 0.35s ease-in-out forwards;
}

@keyframes fadeInStep {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shakeStep {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}

.shake-anim {
    animation: shakeStep 0.4s ease-in-out;
}
</style>

<script>
var currentStep = 0;
var totalSteps = 10;
var generatedCaptcha = '';

function generateCaptchaCode() {
    var chars = '0123456789';
    var code = '';
    for (var i = 0; i < 4; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return code;
}

function refreshCaptchaCode() {
    generatedCaptcha = generateCaptchaCode();
    document.getElementById('captchaDisplay').innerText = generatedCaptcha;
}

function checkStepValidation() {
    var nextBtn = document.getElementById('nextBtn');
    if (!nextBtn) return;

    if (currentStep > 0 && currentStep < totalSteps) {
        var selected = document.querySelector('input[name="q' + currentStep + '"]:checked');
        if (selected) {
            nextBtn.disabled = false;
            nextBtn.style.opacity = '1';
            nextBtn.style.cursor = 'pointer';
        } else {
            nextBtn.disabled = true;
            nextBtn.style.opacity = '0.45';
            nextBtn.style.cursor = 'not-allowed';
        }
    } else {
        nextBtn.disabled = false;
        nextBtn.style.opacity = '1';
        nextBtn.style.cursor = 'pointer';
    }
}

function selectOption(fieldName, value, element) {
    var cards = element.parentElement.querySelectorAll('.option-card');
    cards.forEach(function(card) {
        card.classList.remove('selected-card');
    });
    element.classList.add('selected-card');
    var radio = element.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;

    // Otomatis lanjut ke soal berikutnya (Smooth 220ms transition)
    var stepBefore = currentStep;
    setTimeout(function() {
        if (currentStep === stepBefore && currentStep > 0 && currentStep < totalSteps) {
            currentStep++;
            updateStepUI();
        }
    }, 220);
}

function updateStepUI() {
    for (var i = 0; i <= totalSteps; i++) {
        var el = document.getElementById('step-' + i);
        if (el) {
            if (i === currentStep) {
                el.classList.remove('d-none');
                el.classList.add('active-step');
            } else {
                el.classList.add('d-none');
                el.classList.remove('active-step');
            }
        }
    }

    var progBox = document.getElementById('progressContainer');
    var navBtns = document.getElementById('navButtons');

    if (currentStep === 0) {
        progBox.style.display = 'none';
        navBtns.style.setProperty('display', 'none', 'important');
    } else {
        progBox.style.display = 'block';

        // Calculate progress percentage
        var pct = Math.round((currentStep / totalSteps) * 100);
        document.getElementById('progressBar').style.width = pct + '%';
        document.getElementById('progressBar').setAttribute('aria-valuenow', pct);
        document.getElementById('stepCounterBadge').innerText = 'Soal ' + currentStep + ' / ' + totalSteps;
        document.getElementById('percentBadge').innerText = pct + '% Selesai';

        if (currentStep === totalSteps) {
            navBtns.style.setProperty('display', 'block', 'important');
        } else {
            navBtns.style.setProperty('display', 'none', 'important');
        }
    }
}

function startSurvei() {
    var name = document.getElementById('name').value.trim();
    var phone = document.getElementById('phone').value.trim();
    var gender = document.getElementById('gender').value;
    var age = document.getElementById('age').value.trim();

    if (!name || !phone || !gender || !age) {
        alert('Mohon lengkapi seluruh Data Diri terlebih dahulu sebelum memulai survei!');
        return;
    }
    if (phone.length < 10) {
        alert('Nomor Telepon / WA minimal 10 angka!');
        return;
    }
    if (phone.length > 30) {
        alert('Nomor Telepon / WA maksimal 30 angka!');
        return;
    }
    var ageVal = parseInt(age);
    if (isNaN(ageVal) || ageVal < 17 || ageVal > 120) {
        alert('umur ditolak ga sesuai ketentuan');
        return;
    }

    currentStep = 1;
    updateStepUI();
}

function nextStep() {
    if (currentStep > 0 && currentStep < totalSteps) {
        var selected = document.querySelector('input[name="q' + currentStep + '"]:checked');
        if (!selected) {
            return; // Jangan lanjut jika belum memilih
        }
    }

    if (currentStep < totalSteps) {
        currentStep++;
        updateStepUI();
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepUI();
    } else if (currentStep === 1) {
        currentStep = 0;
        updateStepUI();
    }
}

function openCaptchaModal() {
    refreshCaptchaCode();
    var inputEl = document.getElementById('captchaInput');
    if (inputEl) inputEl.value = '';
    var errBox = document.getElementById('captchaError');
    if (errBox) errBox.classList.add('d-none');
    
    $('#captchaModal').modal('show');
}

function submitFinalForm() {
    var inputCode = document.getElementById('captchaInput').value.trim();
    var errBox = document.getElementById('captchaError');

    if (!inputCode) {
        errBox.innerText = 'Mohon masukkan kode captcha terlebih dahulu!';
        errBox.classList.remove('d-none');
        return;
    }

    if (inputCode.toUpperCase() !== generatedCaptcha.toUpperCase()) {
        errBox.innerText = 'Kode Captcha yang Anda masukkan salah. Silakan coba lagi!';
        errBox.classList.remove('d-none');
        refreshCaptchaCode();
        return;
    }

    errBox.classList.add('d-none');
    var btn = document.getElementById('finalSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim Jawaban...';

    var formData = new FormData(document.getElementById('surveiForm'));
    formData.append('captcha', inputCode);

    fetch('{{ route("survei.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = 'Lanjut kirim jawaban <i class="fas fa-chevron-right ml-1"></i>';
        
        if (data.success) {
            $('#captchaModal').modal('hide');
            document.getElementById('surveiForm').classList.add('d-none');
            document.getElementById('progressContainer').style.display = 'none';
            document.getElementById('navButtons').style.setProperty('display', 'none', 'important');
            document.getElementById('successScreen').classList.remove('d-none');
        } else {
            var msg = data.message || 'Gagal mengirimkan survei.';
            if (data.errors && Object.keys(data.errors).length > 0) {
                var firstKey = Object.keys(data.errors)[0];
                msg = data.errors[firstKey][0];
            }
            errBox.innerText = msg;
            errBox.classList.remove('d-none');
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = 'Lanjut kirim jawaban <i class="fas fa-chevron-right ml-1"></i>';
        errBox.innerText = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        errBox.classList.remove('d-none');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    generatedCaptcha = generateCaptchaCode();

    // Ensure Captcha modal input automatically receives focus when opened
    if (window.jQuery) {
        $('#captchaModal').on('shown.bs.modal', function () {
            $('#captchaInput').focus();
        });
    }
});
</script>
@endsection
