<?php
// Script untuk memperbaiki broken image / symlink di cPanel / Shared Hosting

$target = __DIR__ . '/../storage/app/public';
$shortcut = __DIR__ . '/storage';

echo "<h2>Fix Storage Symlink (cPanel)</h2>";

if (file_exists($shortcut)) {
    echo "<p>Menghapus symlink/folder lama...</p>";
    if (is_link($shortcut)) {
        unlink($shortcut);
    } else {
        // Jika berupa folder fisik atau shortcut windows lama
        exec("rm -rf " . escapeshellarg($shortcut));
        // Fallback PHP rmdir
        @rmdir($shortcut);
    }
}

// Cek apakah target ada
if (!is_dir($target)) {
    echo "<p style='color:red;'>Error: Folder target (storage/app/public) tidak ditemukan!</p>";
    exit;
}

// Buat symlink baru
if (@symlink($target, $shortcut)) {
    echo "<p style='color:green;'><b>SUKSES!</b> Symlink storage berhasil dibuat. Silakan hapus file ini demi keamanan, lalu refresh website Anda.</p>";
} else {
    echo "<p style='color:red;'><b>GAGAL!</b> Fungsi symlink() mungkin diblokir oleh provider hosting cPanel Anda. Coba jalankan lewat terminal cPanel atau kontak bantuan hosting.</p>";
}
