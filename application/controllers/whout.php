<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Whout extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('whout');
	}

	public function create()
	{
		$input = $this->input->post();

		$this->load->model('Whout_order', 'whout', true);

		$this->whout->insert_order();

		$status = $this->db->trans_status() ? 1 : 0;
		$msg = 0==$status ? 'Duplicate order #' : '';

		$ret = array("status"=> $status, 'msg'=> $msg);
		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}

	public function sku_left()
	{
		$this->load->model('Whout_order', 'whout', true);

		$item_sku = $this->input->post('item_sku');
		$left = $this->whout->get_sku_left($item_sku);

		$status = $left>0 ? 1 : 0;
		$ret = array("status"=> $status, "left" => $left);
		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}

	public function detail($oid){
		$this->load->model('Whout_order', 'whout', true);

		$data = $this->whout->get_order_detail($oid);

		$this->load->view('whout_order_detail', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */