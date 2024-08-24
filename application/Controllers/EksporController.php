<?php
namespace App\Controllers;

use App\Models\DataModel;
use CodeIgniter\Controller;

class EksporController extends Controller {

    public function exportJadwalPerkuliahan() {
        if ($this->request->getMethod() !== 'get') {
            return $this->response->setStatusCode(405, 'Method Not Allowed');
        }

        $dataModel = new DataModel();

        try {
            $data = $dataModel->joinDosen();
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500, 'Internal Server Error');
        }

        $filename = 'Data_Perkuliahan_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'No Kelas', 
            'Nama Kelas', 
            'Nama Dosen',
            'Kelas',
            'Mata Kuliah',
            'Hari',
            'Jam Mulai',
            'Jam Akhir'
    ]);
        foreach ($data as $row) {
            fputcsv($output, [
                $row->no_kelas, 
                $row->nama, 
                $row->nm_dosen, 
                $row->nm_kelas, 
                $row->matkul, 
                $row->nm_hari, 
                $row->jam_mulai, 
                $row->jam_akhir
            ]);
        }
        fclose($output);
        exit;
    }
    
    public function exportJadwalPraktikum() {
        if ($this->request->getMethod() !== 'get') {
            return $this->response->setStatusCode(405, 'Method Not Allowed');
        }

        $dataModel = new DataModel();

        try {
            $data = $dataModel->getDosenPraktik();
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500, 'Internal Server Error');
        }

        $filename = 'Data_Jadwal_Praktikum_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Ruangan Praktikum', 
            'Mata Kuliah Praktikum', 
            'Nama Dosen',
            'Nama Kelompok',
            'Hari',
            'Jam Mulai',
            'Jam Akhir'
    ]);
        foreach ($data as $row) {
            fputcsv($output, [
                $row->ruangan_praktikum, 
                $row->mata_kuliah_praktikum, 
                $row->nm_dosen, 
                $row->nama_kelompok, 
                $row->nama_hari, 
                $row->jam_mulai, 
                $row->jam_akhir
            ]);
        }
        fclose($output);
        exit;
    }
}
