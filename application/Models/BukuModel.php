<?php 
namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model{
    protected $table = 'data_buku';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 
        'cover', 
        'tanggal', 
        'created_at',
        'created_by',
        'updated_at',
        'udpated_by',
        'ketersediaan',
        'keterangan',
        'path',
        'status',
        'kode_ebook',
        'judul_ebook',
        'sub_judul_ebook',
        'pengarang',
        'tempat_terbit',
        'penerbit',
        'tahun_terbit',
        'kolase',
        'jilid',
        'edisi',
        'id_kategori_ebook',
        'nama_kategori_ebook',
        'status_boleh_dipinjam',
        'id_klasifikasi',
        'nama_klasifikasi',
        'id_jenis_ebook',
        'nama_jenis_ebook',
        'judul_ebook_rich',
        'sub_judul_ebook_rich',
        'id_bidang',
        'nama_bidang',
        'username',
        'tanggal_upload',
        'waktu_upload',
        'nama_file',
        'url_file'
    ];

}
