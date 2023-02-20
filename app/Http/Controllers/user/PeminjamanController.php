<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pemberitahuan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function riwayatPeminjaman(){
        $peminjamans = Peminjaman::where('user_id', Auth::user()->id)->get();
        return view('user.peminjaman.riwayat', compact('peminjamans'));
    }

    public function formPeminjaman(){
        $bukus = Buku::all();
        return view('user.peminjaman.form', compact('bukus'));
    }

    //SUBMIT FORM PEMINJAMAN
    public function tambahPeminjaman(Request $request){
        $cek_total_peminjaman = Peminjaman::where('user_id', Auth::user()->id)
        ->where('tgl_pengembalian', null)->count();

        //nambah peminjaman
        $peminjaman = Peminjaman::create($request->all());

        //mengurangi jumlah buku baik & rusak saat dipinjam
        $buku = Buku::where('id', $request->buku_id)->first();
        if ($request->kondisi_buku_saat_dipinjam == 'baik') {
            $buku->update([
                'j_buku_baik'=> $buku->j_buku_baik - 1
            ]);
        }
        if ($request->kondisi_buku_saat_dipinjam == 'rusak') {
            $buku->update([
                'j_buku_rusak'=> $buku->j_buku_rusak - 1
            ]);
        }

        // Update Pemberitahuan
        Pemberitahuan::create([
            "isi" => Auth::user()->username . " Meminjam Buku " . $buku->judul,
            "status" => "peminjaman"
        ]);

        if($peminjaman){
            return redirect()->route("user.riwayat_peminjaman")
                ->with("status", "success")
                ->with("message", "Berhasil Menambah Data");
        }
        return redirect()->route("user.riwayat_peminjaman")
            ->with("status", "danger")
            ->with("message", "Gagal menambah data");

    }
}
