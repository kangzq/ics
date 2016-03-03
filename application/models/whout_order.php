<?php

class Whout_order extends CI_Model {

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

    	$this->db->insert('out_orders', array('no'=> $this->order_no, 'created'=>$this->created));

    	$this->order_id = $this->db->insert_id();

    	$this->items = $this->input->post('item');

    	foreach ($this->items as $item) {
            $item_sku = $item["sku"];
            $quantity = intval($item["box_num"]);

            // get storage.id with $item_sku

    		$db_item = array(
    			"oid" => $this->order_id,
    			"item_sku" => $item["sku"],
    			"quantity" => intval($item["box_num"]),
    			"item_from" => $item["quantity"],
                "item_to" => $item["quantity"],
                "remark" => $item["remark"],
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