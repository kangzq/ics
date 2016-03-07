<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Whin extends CI_Controller {

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
		$this->load->helper('form');
		$this->load->view('whin');
	}

	public function create()
	{
		$input = $this->input->post();

		$this->load->model('Whin_order', 'whin', true);

		$this->whin->insert_order();

		$status = $this->db->trans_status() ? 1 : 0;
		$msg = 0==$status ? 'Duplicate order #' : '';

		$ret = array("status"=> $status, 'msg'=> $msg);

		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */