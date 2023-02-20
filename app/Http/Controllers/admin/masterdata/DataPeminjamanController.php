<?php

namespace App\Http\Controllers\admin\masterdata;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DataPeminjamanController extends Controller
{
    public function indexPeminjaman(){
        $peminjaman = Peminjaman::all();
        return view('admin.masterdata.peminjaman', compact('peminjaman'));
    }
}
