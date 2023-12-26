<?php

namespace App\Models;

use CodeIgniter\Model;

class FrontModel extends Model
{
    protected $tablemhs = 'data_mahasiswa';

    public function getMhs($postData)
    {
        $columns = ['id', 'nama', 'npm', 'semester', 'jurusan', 'status_mahasiswa', 'status_perwalian']; // Sesuaikan dengan kolom tabel Anda

        $sql = "SELECT * FROM data_mahasiswa";

        // Cari dan filter
        if (!empty($postData['search']['value'])) {
            $sql .= " WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                $sql .= "$columns[$i] LIKE '%" . $postData['search']['value'] . "%'";
                if ($i < count($columns) - 1) {
                    $sql .= " OR ";
                }
            }
            $sql .= ")";
        }

        // Urutkan
        // if (!empty($postData['order'])) {
        //     $sql .= " ORDER BY $columns[{$postData['order'][0]['column']}] {$postData['order'][0]['dir']}";
        // }

        // Batasan data
        $start = $postData['start'];
        $length = $postData['length'];
        $sql .= " LIMIT $start, $length";

        $query = $this->db->query($sql);
        return $query->getResult();
    }

    public function get()
    {
        $sql = "SELECT * FROM data_mahasiswa";

        $query = $this->db->query($sql);
        return $query->getResult();
    }
}
