<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Controller\BaseController;
use App\Controllers\BaseController as ControllersBaseController;
use App\Models\FrontModel;
use App\Models\MhsModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\Request;
use App\Controllers\Services;

class Jsondatas extends ControllersBaseController
{
    public function getMhs()
    {
        $request = $this->request;
        $mhsmodel = new \App\Models\MhsModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $mhsmodel->getDatatables($request->getPost());
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->npm;
                $row[] = $list->semester;
                $row[] = $list->prodi;
                $row[] = $list->status_mahasiswa == 1 ? '<span class="badge bg-success p-2"> AKTIF </span>' : '<span class="badge bg-danger p-2"> TIDAK AKTIF </span>';
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $mhsmodel->countAll(),
                'recordsFiltered' => $mhsmodel->countFiltered($request->getPost()),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }

    public function getKelas()
    {
        $request = $this->request;
        $mhsmodel = new \App\Models\KelasModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $mhsmodel->getDatatables($request->getPost());
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->no_kelas;
                $row[] = $list->matkul;
                $row[] = '<span class="badge bg-success p-2">' . $list->status . '</span>';
                $row[] = $list->jam_mulai;
                $row[] = $list->jam_akhir;
                $row[] = date('d M Y', strtotime($list->tanggal));
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $mhsmodel->countAll(),
                'recordsFiltered' => $mhsmodel->countFiltered($request->getPost()),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }
    public function getDsn()
    {
        $request = $this->request;
        $dsnmodel = new \App\Models\DosenModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $dsnmodel->getDatatables($request->getPost());
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->mata_kuliah;
                $row[] = $list->jadwal;
                $row[] = $list->kelas;
                $row[] = $list->perkuliahan == 'online' ? '<span class="badge bg-success p-2">Online</span>' : '<span class="badge bg-secondary p-2">Offline</span>';
                $row[] = $list->tugas;
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $dsnmodel->countAll(),
                'recordsFiltered' => $dsnmodel->countFiltered($request->getPost()),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }
}
