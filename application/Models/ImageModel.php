<?php namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model{
  public function insertImage($data = null)
  {
      $res = $this->db->table('data_slider')->insert($data);
      return  $res;
  }

  public function updateImage($id = null, $data = null)
  {
      $res = $this->db->table('data_slider')->where('id', $id)->update($data);
      //  echo $this->db->getLastQuery();die;
      return  $res;
  }

}
