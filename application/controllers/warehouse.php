<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse extends CI_Controller {

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
		$this->load->helper('url');
		$this->load->view('storage');
	}

	public function item_list()
	{
		//$this->load->view('storage');
		//$ret = array("page"=> 1, "total"=> 10, "total_page"=> 3, "list" =>array() );

		$this->load->model('Wh', 'wh', true);

		$ret = $this->wh->load_list();
		
		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}

	public function sku_list(){
		$this->load->model('Wh', 'wh', true);

		$ret = $this->wh->sku_list();
		
		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */