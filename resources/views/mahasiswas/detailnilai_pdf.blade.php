@extends('mahasiswas.layout')

@section('content')
<div class="d-flex align-items-center justify-content-center mt-3 flex-column" style="gap: 40px">
    <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
    <h1 class="">KARTU HASIL STUDI</h1>
</div>
<li class="list-unstyled"><b>Nama : </b>{{$Mahasiswa->Nama}}</li>
<li class="list-unstyled"><b>Nim : </b>{{$Mahasiswa->Nim}}</li>
<li class="list-unstyled"><b>Kelas : </b>{{$Mahasiswa->kelas->nama_kelas}}</li>

<div class="table-responsive mt-5">
    <table class="table table-striped table-hover border">
        <thead>
            <tr>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Nilai</th>
            </tr>
        </thead>

        <tbody>
        @foreach ($MahasiswaMataKuliah as $matkul)
            <tr>
                <td>{{$matkul->matakuliah->nama_matkul}}</td>
                <td>{{$matkul->matakuliah->sks}}</td>
                <td>{{$matkul->matakuliah->semester}}</td>
                <td>{{$matkul->nilai}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection