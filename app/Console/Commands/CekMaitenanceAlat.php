<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class CekMaitenanceAlat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cek-maitenance-alat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomasi peringatan maitenance alat berdasarkan jadwal yang telah ditentukan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $aset_dan_pj = DB::table('aset as a')
                        ->join('pj_maintenance as pm', function($join){
                            $join->on('a.kode_barang_aset', '=', 'pm.kode_barang_aset');
                            $join->where('pm.status', true);
                        })
                        ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                        ->leftJoin(DB::raw('(select kode_barang_aset, max(waktu_maintenance) as last_maintenance
                                                from maintenance_aset as ma
                                                group by kode_barang_aset) as q1'), 'a.kode_barang_aset', '=', 'q1.kode_barang_aset')
                        ->where('a.terjadwal_maintenance', true)
                        ->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang','a.jarak_maintenance_hari', 'u.nama', 'u.id_pengguna_cyber', 'q1.last_maintenance', 'pm.idpj_maintenance')
                        ->get();

        $id_telegram = DB::table('aset as a')
                        ->join('pj_maintenance as pm', function($join){
                            $join->on('a.kode_barang_aset', '=', 'pm.kode_barang_aset');
                            $join->where('pm.status', true);
                        })
                        ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                        ->join('aucc.pengguna as p', 'u.id_pengguna_cyber', '=', 'p.id_pengguna')
                        ->where('a.terjadwal_maintenance', true)
                        ->select('u.id_pengguna_cyber', 'p.id_telegram')
                        ->get();

        $arr_id_telegram = array();
        foreach($id_telegram as $it){
            $arr_id_telegram[$it->id_pengguna_cyber] = $it->id_telegram;
        }


        $arr_pj_maintenance = array();
        foreach($aset_dan_pj as $it){
            if(!array_key_exists($it->id_pengguna_cyber, $arr_pj_maintenance)){
                $arr_pj_maintenance[$it->id_pengguna_cyber] = array(
                    "nama" => $it->nama,
                    "id_telegram" => (array_key_exists($it->id_pengguna_cyber, $arr_id_telegram) ? $arr_id_telegram[$it->id_pengguna_cyber] : null),
                    "idpj_maintenance" => $it->idpj_maintenance,
                    "aset" => array()
                );
            }

            if(!empty($it->last_maintenance)){

                $ts = Carbon::now('Asia/Jakarta');

                $tnow = Carbon::parse($ts);
                $tlast = Carbon::parse($it->last_maintenance);

                $jarak_hari_dari_last_maintenance = $tlast->diffInDays($tnow);

                if($jarak_hari_dari_last_maintenance >= $it->jarak_maintenance_hari){
                    $arr_pj_maintenance[$it->id_pengguna_cyber]["aset"][] = array(
                        "belum_pernah_maintenance" => false,
                        "kode_barang_aset" => $it->kode_barang_aset,
                        "nama_barang" => $it->nama_barang,
                        "merk_barang" => $it->merk_barang,
                        "jarak_maintenance_hari" => $it->jarak_maintenance_hari,
                        "last_maintenance" => $it->last_maintenance,
                        "terlewati_hari" => intval($jarak_hari_dari_last_maintenance - $it->jarak_maintenance_hari)
                    );
                }                
            }
            else{
                $arr_pj_maintenance[$it->id_pengguna_cyber]["aset"][] = array(
                    "belum_pernah_maintenance" => true,
                    "kode_barang_aset" => $it->kode_barang_aset,
                    "nama_barang" => $it->nama_barang,
                    "merk_barang" => $it->merk_barang
                );
            }
        }

        foreach($arr_pj_maintenance as $id_pengguna_cyber => $data_pj){
            $pesan = "👨🏻‍🔬[ SIMANTAP ]👩🏻‍🔬 \n\n Yth. ".$data_pj["nama"].",\n\n";
            $pesan .= "Berikut adalah daftar alat yang menjadi tanggung jawab Anda untuk dilakukan maintenance:\n\n";

            foreach($data_pj["aset"] as $aset){
                if($aset["belum_pernah_maintenance"]){
                    $pesan .= "- 🔬 Alat dengan Kode Barang Aset: ".$aset["kode_barang_aset"]." (".$aset["nama_barang"]." - ".$aset["merk_barang"].") belum pernah dilakukan maintenance sama sekali.\n\n";
                }
                else{
                    $pesan .= "- 🔬 Alat dengan Kode Barang Aset: ".$aset["kode_barang_aset"]." (".$aset["nama_barang"]." - ".$aset["merk_barang"].") terakhir dilakukan maintenance pada tanggal ".$aset["last_maintenance"].", dan sudah melewati jadwal maintenance selama ".$aset["terlewati_hari"]." hari.\n\n";
                }
            }

            $pesan .= "\nMohon segera lakukan tindakan maintenance sesuai jadwal yang telah ditentukan.\n\nTerima kasih.";

            //kirim pesan ke telegram jika id_telegram tersedia
            if(!empty($data_pj["id_telegram"])){
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "https://h2h.unair.ac.id/token/ambil-token-v2");
                curl_setopt($ch2, CURLOPT_POST, 1);
                $param = array(
                    'd3vel' => 'g4RuD@mUkt!',
                    'user' => 'cyberV1',
                    'key' => 'cYberC4mpus@V1',
                );
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $param);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
                $token = str_replace('"', '', curl_exec($ch2));
                curl_close($ch2);
                
                $id_tele = $data_pj["id_telegram"];
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, "https://h2h.unair.ac.id/tele/kirim-tele-v3");
                curl_setopt($ch2, CURLOPT_POST, 1);
                $param = array(
                    'idtele' => $id_tele,
                    'token' => $token,
                    'pesan' => $pesan,
                );
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $param);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
                $result = curl_exec($ch2);
                curl_close($ch2);


                $ts = Carbon::now('Asia/Jakarta');

                $tnow = Carbon::parse($ts);

                DB::table('log_kirim_peringatan')
                    ->insert([
                        'idpj_maintenance' => $data_pj["idpj_maintenance"],
                        'waktu_pengiriman' => $tnow,
                        'pesan_peringatan' => $pesan
                    ]);

            }
        }
    }
}
