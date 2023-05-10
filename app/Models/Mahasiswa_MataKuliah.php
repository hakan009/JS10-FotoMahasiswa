<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa_MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa_matakuliah';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'mahasiswa_id',
        'matakuliah_id',
        'nilai',
    ];

    public function Matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
}
