<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenelitianMasterController extends Controller
{
    public function index(){
        $menu = 'master';
        $submenu = 'penelitian_master';

        // $syarat = DB::table('syarat_ajuan_penelitian')
        //     ->wh;    

        return view('penelitian_master.index');
    }
}
