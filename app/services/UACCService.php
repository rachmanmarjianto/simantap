<?php

namespace App\Services;

class UACCService
{
    public function cari_civitas_akademik(string $nipnik, int $join_table){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apicybercampus.unair.ac.id/api/auth/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('LoginForm[username]' => $nipnik,'LoginForm[password]' => env('AUCC_PASS_KEY')),
        CURLOPT_HTTPHEADER => array(
            'Cookie: _csrf=5436c79c1a6bf2edf9f2b908109f5b5ad3dae5df7f4fe32120368c06a28f5c8da%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22JIkKqqjKxY1NQ1YlOSChBQdkdf3CsCPt%22%3B%7D; uacc-session=eb1cs0g9p54m99riagkk5t88nm'
        ),
        ));

        $response = curl_exec($curl);

        $data = json_decode($response, true);

        if($data['status'] != 'success'){
            return array(
                'code' => 404,
                'message' => $data['message'],
                'data' => null
            );
        }

        $ret_data = array(
            'iduser' => $data['data']['id'],
            'nama' => $data['data']['name'],
            'nipnik' => $data['data']['username'],
            'idfakultas' => $data['data']['fakultas'],
            'idunit_kerja' => $data['data']['homebase_induk']['ID_UNIT_KERJA'],
            'nama_unit_kerja' => $data['data']['homebase_induk']['NM_UNIT_KERJA']
        );

        if($join_table == 2){
            $ret_data['idprogram_studi'] = $data['data']['dosen']['ID_PROGRAM_STUDI'];
        }


        return array(
            'code' => 200,
            'message' => 'success',
            'data' => $ret_data
        );
 
    }
}