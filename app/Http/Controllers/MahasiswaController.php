<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Mahasiswa_MataKuliah;
use App\Models\Matakuliah;
use App\Models\Kelas;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\PDF;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //fungsi eloquent menampilkan data menggunakan pagination
        $mahasiswas = Mahasiswa::paginate(5); // Mengambil semua isi tabel
        $posts = Mahasiswa::orderBy('Nim', 'desc')->paginate(5);
        return view('mahasiswas.index', compact('mahasiswas'))
        ->with('i', (request()->input('page', 1) - 1) * 5); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all(); //Mendapatkan data dari tabel kelas
        return view('mahasiswas.create',['kelas' => $kelas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            if ($request->file('image')){
                $image_name = $request->file('image')->store('images', 'public');
            }
            // Melakukan Validasi Data
                $request->validate([
                    'Nim' => 'required',
                    'Nama' => 'required',
                    'kelas' => 'required',
                    'Jurusan' => 'required',
                    'No_Handphone' => 'required',
                    'Email' => 'required',
                    'Tanggal_Lahir' => 'required',
                ]);
    
            // Fungsi eloquent untuk menambah data
                $mahasiswa = new Mahasiswa;
                $mahasiswa->Nim=$request->get('Nim');
                $mahasiswa->Nama=$request->get('Nama');
                $mahasiswa->Foto=$image_name;
                $mahasiswa->Jurusan=$request->get('Jurusan');
                $mahasiswa->No_Handphone=$request->get('No_Handphone');
                $mahasiswa->Email=$request->get('Email');
                $mahasiswa->Tanggal_Lahir=$request->get('Tanggal_Lahir');
    
            // Fungsi eloquent untuk mmenambah data dengan relasi belongs to
                $kelas = new Kelas;
                $kelas->id = $request->get('kelas');
    
                $mahasiswa->kelas()->associate($kelas);
                $mahasiswa->save();
    
            // Jika data berhasil ditambahkan, akan kembali ke halaman utama
                return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * 
     */
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::find($Nim);
        $kelas = Kelas::all();
        return view('mahasiswas.edit', compact('Mahasiswa','kelas'));
    }
    /**
     * Update the specified resource in storage.
     *
     * 
     */
    public function update(Request $request, $Nim)
    {
        // Melakukan Validasi Data
            $request->validate([
                'Nim' => 'required',
                'Nama' => 'required',
                'kelas' => 'required',
                'Jurusan' => 'required',
                'No_Handphone' => 'required',
                'Email' => 'required',
                'Tanggal_Lahir' => 'required',
            ]);
        
        //fungsi eloquent untuk mengupdate data inputan kita
            $mahasiswa = Mahasiswa::with('kelas')->where('Nim', $Nim)->first();
            if ($mahasiswa->Foto && file_exists(storage_path('app/public/' .$mahasiswa->Foto))) {
                Storage::delete('public/' .$mahasiswa->Foto);
            }
            $image_name = $request->file('image')->store('images', 'public');
            $mahasiswa->Nim=$request->get('Nim');
            $mahasiswa->Nama=$request->get('Nama');
            $mahasiswa->Foto=$image_name;
            $mahasiswa->Jurusan=$request->get('Jurusan');
            $mahasiswa->No_Handphone=$request->get('No_Handphone');
            $mahasiswa->Email=$request->get('Email');
            $mahasiswa->Tanggal_Lahir=$request->get('Tanggal_Lahir');

            $kelas = new Kelas;
            $kelas->id = $request->get('kelas');

        // Fungsi eloquent untuk mengedit data dengan relasi belongs to
            $mahasiswa->kelas()->associate($kelas);
            $mahasiswa->save();

        //jika data berhasil diupdate, akan kembali ke halaman utama
            return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
            Mahasiswa::find($Nim)->delete();
            return redirect()->route('mahasiswa.index')
            -> with('success', 'Mahasiswa Berhasil Dihapus');
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $mahasiswas = Mahasiswa::where('Nama', 'like', "%" . $keyword . "%")->paginate(1);
        return view('mahasiswas.index', compact('mahasiswas'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    // public function detailnilai($Nim)
    // {
    //     // menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
    //     $Mahasiswa = Mahasiswa::with('matakulias')->where('Nim', $Nim)->first();
    //     $nilai = DB::table('mahasiswa_matakuliah')
    //         ->join('matakuliah', 'matakuliah.id', '=', 'mahasiswa_matakuliah.matakuliah_id')
    //         ->where('mahasiswa_matakuliah.mahasiswa_id')
    //         ->select('nilai')
    //         ->get();
    //     return view('mahasiswas.detailnilai', ['Mahasiswa' => $Mahasiswa,'nilai' => $nilai]);
    // }

    

    public function nilai($Nim)
    {
        $Mahasiswa = Mahasiswa::find($Nim);
        $Matakuliah = Matakuliah::all();
        $Mahasiswa_MataKuliah = Mahasiswa_MataKuliah::where('mahasiswa_id','=',$Nim)->get();
        return view('mahasiswas.detailnilai',['Mahasiswa' => $Mahasiswa],['MahasiswaMataKuliah' => $Mahasiswa_MataKuliah], compact('Mahasiswa_MataKuliah'));
    }

    public function cetak_pdf($Nim){
        $Mahasiswa = Mahasiswa::find($Nim);
        $Matakuliah = Matakuliah::all();
        $MahasiswaMataKuliah = Mahasiswa_MataKuliah::where('mahasiswa_id','=',$Nim)->get();
        $pdf = PDF::loadview('mahasiswas.detailnilai_pdf', compact('Mahasiswa','MahasiswaMataKuliah'));
        return $pdf->stream();
    }

};
