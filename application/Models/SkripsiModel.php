<?php
namespace App\Models;

use CodeIgniter\Model;

class SkripsiModel extends Model
{
    protected $table = 'data_skripsi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'no_induk_buku',
        'kode_buku',
        'judul_buku',
        'sub_judul_buku',
        'pengarang',
        'tempat_terbit',
        'penerbit',
        'tahun_terbit',
        'kolase',
        'jilid',
        'edisi',
        'id_kategori_buku',
        'nama_kategori_buku',
        'status_boleh_dipinjam',
        'id_klasifikasi',
        'nama_klasifikasi',
        'id_sumber_buku',
        'sumber_buku',
        'id_lokasi',
        'id_tempat_buku',
        'nama_tempat_buku',
        'kategori_tempat_buku',
        'baris',
        'tanggal_masuk',
        'tanggal_masuk_formated',
        'harga_buku',
        'status_ketersediaan',
        'kondisi_buku',
        'status_aktif',
        'no_panggil',
        'created_at',
        'path',
        'url_file'
    ];

    public function deleteData($id = null, $table = null)
    {
        $res = $this->db->table("data_$table")->where('id', $id)->delete();
        return  $res;
    }
}
