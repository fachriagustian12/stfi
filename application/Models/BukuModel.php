<?php 
namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model{
    protected $table = 'data_buku';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title','create_date','create_by','update_date','udpate_by','ketersediaan','keterangan','path','status'];

}
