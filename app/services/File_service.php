<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class File_service
{
    public function get_file($idfile, $tipe_kegiatan){

        // dd($idfile, $tipe_kegiatan);

        if($tipe_kegiatan == 'maintenance') {
            $file = DB::table('file_maintenance')
                        ->where('idfile_maintenance', $idfile)
                        ->first();
        }
        else if($tipe_kegiatan == 'penelitian') {
            $file = DB::table('file_ajuan_penelitian')
                        ->where('idfile_ajuan_penelitian', $idfile)
                        ->first();
        }
        else {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Tipe kegiatan tidak valid'
            ]);
        }

        // dd($file);

        if($file){
            $file_path = $file->file_path;
            if(Storage::exists($file_path)){
                return Storage::response($file_path, $file->nama_file);
            }
        }
    }

    public function hapus_file($idfile, $tipe_kegiatan){
        if($tipe_kegiatan == 'maintenance') {
            $file = DB::table('file_maintenance')
                        ->where('idfile_maintenance', $idfile)
                        ->first();
        }
        else if($tipe_kegiatan == 'penelitian') {
            $file = DB::table('file_ajuan_penelitian')
                        ->where('idfile_ajuan_penelitian', $idfile)
                        ->first();
        }
        else {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Tipe kegiatan tidak valid'
            ]);
        }

        if($file){
            $file_path = $file->file_path;
            if(Storage::exists($file_path)){
                Storage::delete($file_path);
            }

            if($tipe_kegiatan == 'maintenance') {
                DB::table('file_maintenance')
                    ->where('idfile_maintenance', $idfile)
                    ->delete();
            }
            else if($tipe_kegiatan == 'penelitian') {
                date_default_timezone_set('Asia/Jakarta');
                $ts = date('Y-m-d H:i:s');

                DB::table('file_ajuan_penelitian')
                    ->where('idfile_ajuan_penelitian', $idfile)
                    ->update(
                        [
                            'nama_file' => null,
                            'file_path' => null,
                            'updated_at' => $ts,
                            'updated_by' => session('userdata')['iduser']
                        ]
                    );
            }

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'File berhasil dihapus'
            ]);
        }
        else{
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'File tidak ditemukan'
            ]);
        }
    }
}