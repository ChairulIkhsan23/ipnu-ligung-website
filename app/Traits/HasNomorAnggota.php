<?php

namespace App\Traits;

trait HasNomorAnggota
{
    public static function generateNomorAnggota($position = null)
    {
        $kodeOrganisasi = 'PAC-LIGUNG';
        
        // Gunakan helper method untuk kode jabatan
        $kodeJabatan = self::generateKodeJabatan($position);
        
        $tahun = date('Y');
        
        // Cari nomor terakhir dengan format yang sama - batasi dengan tahun
        $lastNumber = self::where('nomor_anggota', 'like', "{$kodeOrganisasi}/{$kodeJabatan}/{$tahun}/%")
            ->orderBy('nomor_anggota', 'desc')
            ->first();
        
        if ($lastNumber && preg_match('/\/(\d+)$/', $lastNumber->nomor_anggota, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        $generated = "{$kodeOrganisasi}/{$kodeJabatan}/{$tahun}/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        // Cek duplikat dengan limit attempt
        $attempts = 0;
        while (self::where('nomor_anggota', $generated)->exists() && $attempts < 5) {
            $nextNumber++;
            $generated = "{$kodeOrganisasi}/{$kodeJabatan}/{$tahun}/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        return $generated;
    }
    
    // Method khusus untuk generate kode jabatan (non-static untuk menghindari conflict)
    private static function generateKodeJabatan($position)
    {
        if (!$position) return 'UMUM';
        
        $position = strtolower($position);
        
        if (str_contains($position, 'ketua')) return 'KT';
        if (str_contains($position, 'sekretaris')) return 'SEK';
        if (str_contains($position, 'bendahara')) return 'BEN';
        if (str_contains($position, 'anggota')) return 'AGT';
        if (str_contains($position, 'departemen')) return 'DEP';
        if (str_contains($position, 'bidang')) return 'BDG';
        
        return 'UMUM';
    }
    
    // Method untuk update kode jabatan (jika perlu)
    public static function getKodeFromPosition($position)
    {
        return self::generateKodeJabatan($position);
    }
}