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
		$this->load->helper(['form','url']);
		$this->load->view('whin');
	}

	public function create()
	{
		$input = $this->input->post();

		$this->load->model('Whin_order', 'whin', true);

		$status = $this->whin->insert_order();

		$status = $this->db->trans_status() ? 1 : 0;
		if(0==$status){
			$status = $this->whin->update_order();
		}

		$msg = 0==$status ? 'Duplicate order #' : '';

		$ret = array("status"=> $status, 'msg'=> $msg);

		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}

	public function detail($oid)
	{
		$this->load->helper(['form','url']);
		if(empty($oid)){
			redirect('whin', 'location', 301);
		}

		$this->load->model('Whin_order', 'whin', true);
		
		$in_order = $this->whin->get_order($oid);
		
		// deleted
		if(count($in_order)==0) redirect('whin', 'location', 301);

		$order_no = count($in_order)>0 ? $in_order[0]['no'] : '';


		$this->load->view('whin', ['items'=>$in_order, 'input_no' => $order_no, 'order_id'=> $oid]);
	}

	public function edit_save()
	{
		$status = $msg = '1';
		$ret = array("status"=> $status, 'msg'=> $msg);

		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}

	public function drop()
	{
		$item_id = intval($this->input->post('item_id'));
		$order_id = intval($this->input->post('order_id'));

		$status = $msg = '0';
		$this->load->model('Whin_order', 'whin', true);
		if($item_id>0 && 0== $order_id && $this->whin->can_remove_item($item_id)){

			$status = $this->whin->remove_item($item_id);
			$msg = ((0!=$status) ? '' : 'This item cant be removed.');
		}elseif($item_id>0){
			$msg = 'This item cant be removed.';
		}

		if($order_id>0 && 0== $item_id && $this->whin->can_remove_order($order_id)){
			$status = $this->whin->remove_order($order_id);
			$msg = ((0!=$status) ? '' : 'This order cant be removed.');
		}elseif($order_id>0){
			$msg = 'This order cant be removed.';
		}

		$ret = array("status"=> $status, 'msg'=> $msg);

		header("Content-type:application/json;charset=utf8");
		exit(json_encode($ret));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */