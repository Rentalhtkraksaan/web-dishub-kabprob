<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgChart;

class OrgChartSeeder extends Seeder
{
    public function run()
    {
        if (OrgChart::count() > 0) return;

        // 1. Kepala Dinas (Puncak Hirarki)
        $kadis = OrgChart::create([
            'parent_id' => null,
            'title' => 'Kepala Dinas Perhubungan',
            'name' => 'EDWAN YUDIYANTO, S.Sos., M.Si.',
            'nip' => '19740512 199311 1 001',
            'image_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 1
        ]);

        // 2. Sekretaris (Bawahan Langsung Kadis)
        $sekretaris = OrgChart::create([
            'parent_id' => $kadis->id,
            'title' => 'Sekretaris Dinas',
            'name' => 'BAMBANG HERIWIBOWO, S.H., M.M.',
            'nip' => '19760815 199803 1 004',
            'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 1
        ]);

        // 3. Kepala Bidang (Sejajar Di Bawah Kadis & Sekretaris)
        $kabid1 = OrgChart::create([
            'parent_id' => $kadis->id,
            'title' => 'Kabid Pengujian & Keselamatan',
            'name' => 'H. ACHMAD FARIED, S.T., M.T.',
            'nip' => '19800310 200501 1 008',
            'image_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 2
        ]);

        $kabid2 = OrgChart::create([
            'parent_id' => $kadis->id,
            'title' => 'Kabid Rekayasa Lalu Lintas',
            'name' => 'RUDY SULISTYONO, S.AT., M.Si.',
            'nip' => '19821104 200604 1 009',
            'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 3
        ]);

        $kabid3 = OrgChart::create([
            'parent_id' => $kadis->id,
            'title' => 'Kabid Angkutan & Perparkiran',
            'name' => 'SUHARTO, S.E.',
            'nip' => '19780219 200112 1 003',
            'image_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 4
        ]);

        // 4. Sub Bagian / Kasi (Di Bawah Kabid / Sekretaris)
        OrgChart::create([
            'parent_id' => $sekretaris->id,
            'title' => 'Kasubag Umum & Kepegawaian',
            'name' => 'NURUL HIDAYATI, S.AP.',
            'nip' => '19850612 201001 2 015',
            'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 1
        ]);

        OrgChart::create([
            'parent_id' => $kabid1->id,
            'title' => 'Kasi Pengujian Kendaraan Bermotor',
            'name' => 'DWI CHANDRA, A.Md.LLAJ',
            'nip' => '19880921 201202 1 002',
            'image_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 1
        ]);

        OrgChart::create([
            'parent_id' => $kabid2->id,
            'title' => 'Kasi Sarana & Prasarana Rambu PJU',
            'name' => 'AGUS SETIAWAN, S.T.',
            'nip' => '19840417 200801 1 011',
            'image_url' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=400&h=400&fit=crop',
            'line_type' => 'command',
            'order_no' => 1
        ]);
    }
}
