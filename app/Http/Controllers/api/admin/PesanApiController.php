<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanApiController extends Controller
{
    //KIRIM PESAN
    public function pesan_terkirim(){
        $pesan = Pesan::where('penerima_id', '!=', Auth::user()->id )
        ->where('pengirim_id', Auth::user()->id)
        ->get();
        // $penerimas = User::where('role', 'admin')
        // ->get();
        return response()->json([
            'data' => $pesan,
            'msg' => 'Pesan Berhasil Dikirim'
        ]);
    }

    public function kirim_pesan(Request $request)
    {
        $pesan = Pesan::create($request->all());
        $admin = User::where('id', $request->penerima_id)->first();
        return response()->json([
            'data' => $pesan,
            'msg' => 'Pesan Berhasil Dikirim'
        ]);
    }   
}
