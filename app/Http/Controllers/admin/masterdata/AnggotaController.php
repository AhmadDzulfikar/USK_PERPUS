<?php

namespace App\Http\Controllers\admin\masterdata;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index_anggota()
    {
        $total = User::where('role', 'user')->count();
        $kode = 'AA00' . $total + 1;
        $anggota = User::where('role', 'user')->get();
        return view('admin.masterdata.anggota', compact('anggota', 'kode'));
    }

    public function tambahAnggota(Request $request)
    {
        $kode = User::where('role', 'user')->count();

        $anggota = User::create([
            'kode' => 'AA00' . $kode + 1,
            'nis' => $request->nis,
            'fullname' => $request->fullname,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'kelas' => $request->kelas,
            'verif' => 'verified',
            'alamat' => $request->alamat,
            'role' => 'user',
            'join_date' => Carbon::now()
        ]);

        if ($anggota) {
            return redirect()
                ->back()
                ->with("status", "success")
                ->with("message", "Berhasil Menambah Data");
        }
        return redirect()->back()
            ->with("status", "danger")
            ->with("message", "Gagal menambah data");
    }

    public function ubahAnggota(Request $request , $id)
    {
        $anggota = User::where('role' , 'user')->where('id' , $id); 
        $anggota->update([
            'nis' => $request->nis,
            'username' => $request->username,
            'fullname' => $request->fullname,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat,
            // 'verif' => $request->verif_id
        ]);

        return redirect()->back();
    }

    public function updateStatus($id, Request $request){
        $anggota = User::where('id', $id)->first();

        if ($anggota != null) {

            if ($request->verif == 'unverified') {
                $anggota->update([
                    'verif' => 'verified'
                ]);
            }

            return redirect()
                ->back()
                ->with("status", "success")
                ->with("message", "Berhasil Merubah Status ");
        }
        return redirect()->back()
            ->with("status", "danger")
            ->with("message", "Gagal Merubah Status");
    }
    public function hapusAnggota($id){
        $anggota = User::where('role' , 'user')->where('id' , $id);
        $anggota->delete();
        
        return redirect()->back();
    }
}
