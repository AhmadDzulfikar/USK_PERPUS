<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    //MASUK PESAN
    public function pesan_masuk(Request $request)
    {
        $masuk = Pesan::where('pengirim_id', '!=', Auth::user()->id)
            ->where('penerima_id', Auth::user()->id)
            ->get();

        return view('user.pesan.masuk', compact('masuk'));
    }

    public function ubah_status(Request $request)
    {
        $status = Pesan::where('id', $request->id)->first();
        $status->update([
            'status' => 'dibaca'
        ]);
        return redirect()->route('user.pesan_masuk');
    }

    //KIRIM PESAN
    public function kirim(){
        $pesan = Pesan::where('penerima_id', '!=', Auth::user()->id )
        ->where('pengirim_id', Auth::user()->id)
        ->get();
        $penerimas = User::where('role', 'admin')
        ->get();
        return view('user.pesan.terkirim', compact('pesan', 'penerimas'));
    }

    public function kirim_pesan(Request $request)
    {
        $pesan = Pesan::create($request->all());
        $admin = User::where('id', $request->penerima_id)->first();
        return redirect()
            ->back()
            ->with('status', 'success')
            ->with('message', "Berhasil mengirim pesan ke $admin->fullname");
    }
}
