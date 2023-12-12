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
                $row[] = $list->jurusan;
                $row[] = $list->jurusan;
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
        $output = [
            'draw' => 0,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ];

        echo json_encode($output);
    }
}
