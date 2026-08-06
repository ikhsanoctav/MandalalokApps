<?php

namespace App\Services;

use App\Models\UMKM;
use App\Models\DssKriteria;

class DssService
{
    public function calculateSAW($tipeDss)
    {
        $kriterias = DssKriteria::where('tipe_dss', $tipeDss)->where('aktif', true)->get();
        if ($kriterias->isEmpty()) {
            return collect([]);
        }

        $umkms = UMKM::where('status_verifikasi', 'terverifikasi')->with(['pemilik', 'sektor'])->get();
        if ($umkms->isEmpty()) {
            return collect([]);
        }

        // 1. Ekstraksi Nilai Mentah (Matriks Keputusan)
        $matrix = [];
        $minMax = [];
        
        foreach ($kriterias as $k) {
            $minMax[$k->kode_kriteria] = [
                'min' => null,
                'max' => null,
            ];
        }

        $currentYear = (int) date('Y');

        foreach ($umkms as $umkm) {
            $row = ['umkm' => $umkm];
            
            // Evaluasi Kriteria Bantuan
            if ($tipeDss === 'bantuan') {
                // B1: Legalitas (NIB & NPWP)
                $scoreLegal = 1;
                if (!empty($umkm->no_izin_usaha) && !empty($umkm->npwp_usaha)) $scoreLegal = 3;
                elseif (!empty($umkm->no_izin_usaha) || !empty($umkm->npwp_usaha)) $scoreLegal = 2;
                $row['B1'] = $scoreLegal;

                // B2: Lama Usaha
                $lamaUsaha = $umkm->tahun_berdiri ? ($currentYear - $umkm->tahun_berdiri) : 0;
                $row['B2'] = $lamaUsaha < 1 ? 1 : $lamaUsaha;

                // B3: Jumlah Pekerja
                $row['B3'] = ($umkm->jumlah_tenaga_kerja ?? 0) + 1;

                // B4: Riwayat Bantuan
                $row['B4'] = empty($umkm->riwayat_bantuan) ? 1 : 2;
            }
            // Evaluasi Kriteria Pelatihan
            else if ($tipeDss === 'pelatihan') {
                // P1: Legalitas
                $scoreLegal = 1;
                if (!empty($umkm->no_izin_usaha) && !empty($umkm->npwp_usaha)) $scoreLegal = 3;
                elseif (!empty($umkm->no_izin_usaha) || !empty($umkm->npwp_usaha)) $scoreLegal = 2;
                $row['P1'] = $scoreLegal;

                // P2: Lama Usaha
                $lamaUsaha = $umkm->tahun_berdiri ? ($currentYear - $umkm->tahun_berdiri) : 0;
                $row['P2'] = $lamaUsaha < 1 ? 1 : $lamaUsaha;

                // P3: Jumlah Pekerja
                $row['P3'] = ($umkm->jumlah_tenaga_kerja ?? 0) + 1;

                // P4: Bentuk Jualan
                $scoreJualan = 1; // Keliling / Rumahan / Lainnya
                if (in_array($umkm->bentuk_jualan, ['Toko Online', 'Hybrid (Fisik & Online)'])) {
                    $scoreJualan = 3;
                } else if ($umkm->bentuk_jualan === 'Toko Fisik') {
                    $scoreJualan = 2;
                }
                $row['P4'] = $scoreJualan;
            }

            // Update Min Max
            foreach ($kriterias as $k) {
                $val = $row[$k->kode_kriteria] ?? 1;
                if ($minMax[$k->kode_kriteria]['min'] === null || $val < $minMax[$k->kode_kriteria]['min']) {
                    $minMax[$k->kode_kriteria]['min'] = $val;
                }
                if ($minMax[$k->kode_kriteria]['max'] === null || $val > $minMax[$k->kode_kriteria]['max']) {
                    $minMax[$k->kode_kriteria]['max'] = $val;
                }
            }

            $matrix[] = $row;
        }

        // 2. Normalisasi & Hitung Total Skor
        $results = [];
        
        foreach ($matrix as $row) {
            $totalScore = 0;
            $normalizedRow = [];
            
            foreach ($kriterias as $k) {
                $code = $k->kode_kriteria;
                $val = $row[$code] ?? 1;
                $normVal = 0;

                // Hindari division by zero
                $max = empty($minMax[$code]['max']) ? 1 : $minMax[$code]['max'];
                $min = empty($minMax[$code]['min']) ? 1 : $minMax[$code]['min'];
                $val = ($val == 0) ? 1 : $val; // fallback zero val

                if ($k->jenis === 'benefit') {
                    $normVal = $val / $max;
                } else {
                    $normVal = $min / $val;
                }
                
                $normalizedRow[$code] = $normVal;
                
                // Weighting (bobot di DB dalam bentuk persen 1-100, kita ubah jadi 0.01 - 1.0)
                $weight = $k->bobot / 100;
                $totalScore += ($normVal * $weight);
            }
            
            $results[] = [
                'umkm' => $row['umkm'],
                'skor_akhir' => round($totalScore * 100, 2), // Jadikan persentase 0 - 100
                'detail' => $normalizedRow
            ];
        }

        // 3. Sorting berdasar skor akhir (Descending)
        usort($results, function($a, $b) {
            return $b['skor_akhir'] <=> $a['skor_akhir'];
        });

        return collect($results);
    }
}
