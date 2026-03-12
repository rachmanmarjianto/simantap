<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PenelitianMahasiswaController extends Controller
{
    public function index(){
        $menu = 'penelitian';
        $submenu = 'sub_penelitian';

        $list_ajuan_penelitian = DB::table('penelitian as p')
                                        ->join('tim_mahasiswa as tm', 'p.idpenelitian', '=', 'tm.idpenelitian')
                                        ->where('tm.iduser_mahasiswa', session('userdata')['iduser'])
                                        ->select('p.*')
                                        ->get();

        return view('mahasiswa.index', compact('menu', 'submenu', 'list_ajuan_penelitian'));
    }
}
