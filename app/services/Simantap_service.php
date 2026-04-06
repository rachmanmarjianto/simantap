<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class Simantap_service
{
    public function cek_unit_kerja($idunitkerja){
        $cekunit_kerja = DB::table('unit_kerja_simantap')
							->where('idunit_kerja_simantap', $idunitkerja)
							->exists();

		if(!$cekunit_kerja){
			$unit_kerja_baru = DB::table('aucc.unit_kerja')
							->where('id_unit_kerja', $idunitkerja)
							->first();

            try {
                if($unit_kerja_baru->type_unit_kerja == 'FAKULTAS'){
                    DB::table('unit_kerja_simantap')->insert([
                        'idunit_kerja_simantap' => $idunitkerja,
                        'layanan' => 'false',
                        'penelitian' => 'false',
                        'praktikum' => 'true',
                        'training' => 'false',
                    ]);
                }
                else{
                    DB::table('unit_kerja_simantap')->insert([
                        'idunit_kerja_simantap' => $idunitkerja,
                        'layanan' => 'true',
                        'penelitian' => 'true',
                        'praktikum' => 'true',
                        'training' => 'true',
                    ]);
                }

                return [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Unit kerja berhasil ditambahkan ke unit_kerja_simantap'
                ];
            } catch (\Exception $e) {
                \Log::error('Error inserting unit_kerja_simantap: ' . $e->getMessage());
                return [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Gagal menambahkan unit kerja ke unit_kerja_simantap: ' . $e->getMessage()
                ];
            }
		}
        else{
            return [
                'code' => 200,
                'status' => 'success',
                'message' => 'Unit kerja sudah ada di unit_kerja_simantap'
            ];
        }
    }
}
