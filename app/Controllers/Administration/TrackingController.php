<?php

namespace App\Controllers\Administration;
use App\Controllers\BaseController;
use App\Libraries\KerpClient;

class TrackingController extends BaseController
{
    protected $directoryModel, $countryModel, $cityModel, $categoryModel;
    protected $session;

    public function __construct()
    {
        /*
        $this->directoryModel = new DirectoryModel();
        $this->countryModel = new CountryModel();
        $this->cityModel = new CityModel();
        $this->categoryModel = new CategoryModel();
        $this->session = session();
        */
    }

    public function index()
    {
        $kerp = new KerpClient();
        $resultado = $kerp->listarMisTramites();
        var_dump(json_encode($resultado));
        $data = [
            'title' => "Seguimiento de tramites",
            'titleMod' => "Administración y Seguimiento de tramites",
        ];

        return view('administration/tracking', $data);
    }

    // DataTables AJAX
    public function getData()
    {
        $request = $this->request->getPost();

        $data = $this->directoryModel->getDataTable($request);
        $total = $this->directoryModel->countAllData();
        $filtered = $this->directoryModel->countFilteredData($request);

        return $this->response->setJSON([
            'draw' => intval($request['draw']),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ]);
    }
}
