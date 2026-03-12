<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FilestorageController extends Controller
{
    public function get_file($id){
        $idfile = decrypt($id);
        $file = DB::table('file_maintenance')
                    ->where('idfile_maintenance', $idfile)
                    ->first();

        // dd($file);

        if($file){
            $file_path = $file->file_path;
            if(Storage::exists($file_path)){
                return Storage::response($file_path, $file->nama_file);
            }
        }
    }

    public function hapus_file(Request $request){
        $idfile = $request->idfile_maintenance;
        $file = DB::table('file_maintenance')
                    ->where('idfile_maintenance', $idfile)
                    ->first();

        if($file){
            $file_path = $file->file_path;
            if(Storage::exists($file_path)){
                Storage::delete($file_path);
            }

            DB::table('file_maintenance')
                ->where('idfile_maintenance', $idfile)
                ->delete();

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
