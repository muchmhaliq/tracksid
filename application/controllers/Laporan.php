<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->model('desa_model');
    $this->load->helper('url');
    session_start();
  }

  public function index($offset=0)
  {
    // if(isset($_SESSION['filter']))
    //   $data['filter'] = $_SESSION['filter'];
    // else $data['filter'] = '';

    // $data_desa = $this->desa_model->list_desa($offset);
    // $data['list_desa'] = $data_desa['list_desa'];
    // $data['links'] = $data_desa['links'];
    // $data['offset'] = $offset;
    // $this->load->view('list_desa', $data);
    $this->load->view('ajax_list_desa');
  }

  public function filter(){
    $filter = $this->input->post('filter');
    if($filter!=0)
      $_SESSION['filter']=$filter;
    else unset($_SESSION['filter']);
    redirect("/laporan");
  }

  public function ajax_list_desa()
  {
    $list = $this->desa_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $desa) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $desa['nama_desa'];
      $row[] = $desa['nama_kecamatan'];
      $row[] = $desa['nama_kabupaten'];
      $row[] = $desa['nama_provinsi'];
      $row[] = isset($desa['url_referrer']) ? $desa['url_referrer'] : '';
      $row[] = $desa['opensid_version'];
      $row[] = $desa['tgl'];

      $data[] = $row;
    }

    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->desa_model->count_all(),
            "recordsFiltered" => $this->desa_model->count_filtered(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


}
