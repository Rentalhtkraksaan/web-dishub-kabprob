<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Developer Security & Access Recovery | DISHUB Kab. Probolinggo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            800: '#0f172a',
                            900: '#0a0f1d',
                            950: '#050811'
                        },
                        accent: '#3b82f6'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-navy-950 text-slate-100 font-sans min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Subtle Background Glow Elements -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Header / Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-navy-900 border border-slate-800 shadow-xl mb-4 text-blue-500">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
            <h1 class="font-heading font-extrabold text-2xl tracking-tight text-white mb-1">
                Developer Recovery Portal
            </h1>
            <p class="text-xs text-slate-400">
                Sistem Pemulihan Akses Superadmin DISHUB Kab. Probolinggo
            </p>
        </div>

        <!-- Card Form -->
        <div class="bg-navy-900/80 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/50 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-medium p-3.5 rounded-2xl flex items-center gap-2.5">
                    <i class="fas fa-check-circle text-emerald-400 text-base shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-medium p-3.5 rounded-2xl flex items-center gap-2.5">
                    <i class="fas fa-exclamation-triangle text-rose-400 text-base shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ url('/recovery') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="developer_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        ID Developer Pembuat
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fas fa-key text-sm"></i>
                        </div>
                        <input type="password" 
                               id="developer_id" 
                               name="developer_id" 
                               required 
                               placeholder="Masukkan Kode Verification ID"
                               autocomplete="off"
                               class="w-full pl-10 pr-4 py-3 bg-navy-950 border border-slate-700/80 rounded-2xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition duration-200">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2 flex items-center gap-1.5">
                        <i class="fas fa-lock text-[10px] text-amber-500"></i>
                        Verifikasi terenkripsi standar keamanan SHA-256.
                    </p>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-blue-600/25 transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-unlock-alt"></i> Pulihkan & Masuk Superadmin
                </button>
            </form>

            <div class="pt-4 border-t border-slate-800/80 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-slate-200 transition inline-flex items-center gap-1.5">
                    <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Halaman Utama
                </a>
            </div>

        </div>

        <p class="text-center text-[11px] text-slate-600 mt-6">
            &copy; 2026 Dinas Perhubungan Kabupaten Probolinggo. Confidential System.
        </p>

    </div>

</body>
</html>
