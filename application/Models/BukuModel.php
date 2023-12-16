<?php 
namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model{
    protected $table = 'data_buku';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'tanggal', 'created_at','created_by','updated_at','udpated_by','ketersediaan','keterangan','path','status'];

}
