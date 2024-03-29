<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Identitas;
use Illuminate\Http\Request;

class IdentitasController extends Controller
{
    public function index_identitas()
    {
        $identitas = Identitas::first();
        return view('admin.identitas', compact('identitas'));
    }

    public function ubah_identitas(Request $request)
    {

        $identitas = Identitas::first();
        if ($request->foto == null) {
            $identitas->update([
                'nama_app' => $request->nama_app,
                'email_app' => $request->email_app,
                'nomor_hp' => $request->nomor_hp,
                'alamat_hp' => $request->alamat_hp,
            ]);
        } else {
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('img'), $imageName);
            $identitas->update([
                'nama_app' => $request->nama_app,
                'email_app' => $request->email_app,
                'nomor_hp' => $request->nomor_hp,
                'alamat_hp' => $request->alamat_hp,
                "foto" => "/img/" . $imageName
            ]);
        }
    return redirect()->back()->with("status", "danger")->with('message', 'Gagal mengubah profile');

    }
}
