<?php

class Whin_order extends CI_Model {

    var $order_no   = '';
    var $order_id   = 0;
    var $items = array();
    var $created = 0;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    // in order
    function insert_order()
    {	
    	$this->order_no = $this->input->post('input_no');
    	$this->created = time();

    	$this->db->trans_start();

    	$this->db->insert('in_orders', array('no'=> $this->order_no, 'created'=>$this->created));

    	$this->order_id = $this->db->insert_id();

    	$this->items = $this->input->post('item');

    	foreach ($this->items as $item) {
    		$db_item = array(
    			"oid" => $this->order_id,
    			"item_sku" => $item["sku"],
    			"pallet_id" => $item["pallet_id"],
    			"is_packed" => isset($item["packed"]) ? 1:0,
    			"box_num" => intval($item["box_num"]),
    			"box_id" => $item["box_id"],
    			"box_capacity" => $item["box_capacity"],
    			"item_quantity" => $item["quantity"],
    			"item_left" => intval($item["box_num"]),
    			"created" => $this->created
    		);

    		$this->db->insert('storage', $db_item);
    	}

    	$this->db->trans_complete();
    }

    /*
    function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function insert_entry()
    {
        $this->title   = $_POST['title']; // please read the below note
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->insert('entries', $this);
    }

    function update_entry()
    {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }
	*/
}