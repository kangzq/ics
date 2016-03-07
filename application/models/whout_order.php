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
            $this->db->select("id,item_left");
            $this->db->from('storage');
            $this->db->where('item_sku', $item_sku);
            $this->db->order_by('created','asc');
            $query = $this->db->get();

            foreach ($query->result_array() as $row)
            {
                // update storage table item_left
                $left = $row['item_left'] - $quantity;
                $tmp_qtt = $quantity;
                if($left<0){
                    $left = 0;
                    $tmp_qtt = $row['item_left'];
                }
                $this->db->update('storage', array('item_left'=> $left), array('id'=>$row["id"] ));

                // insert out_order_detail
                $db_item = array(
                    "oid" => $this->order_id,
                    "item_id" => $row["id"],
                    "quantity" => intval($tmp_qtt),
                    "item_from" => $item["item_from"],
                    "item_to" => $item["item_to"],
                    "remark" => $item["remark"]
                );

                $this->db->insert('out_order_detail', $db_item);

                if($row['item_left'] > $quantity){
                    break;
                }
                else{
                    $quantity -= $row['item_left'];
                }
            }
    	}

    	$this->db->trans_complete();
    }

    function get_sku_left($item_sku){
        $this->db->select_sum('item_left');
        $this->db->from('storage');
        $this->db->where('item_sku', $item_sku);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
           $row = $query->row_array();
           return intval($row['item_left']);
        }
        return 0;
    }

    function get_order_detail($oid){
        $this->db->select();
        $this->db->from('out_orders');
        $this->db->where('id', $oid);
        $query = $this->db->get();
        $ret = array();
        if ($query->num_rows() > 0){
           $ret = $query->row_array();
        }

        $this->db->select();
        $this->db->from('out_order_detail o');
        $this->db->join('storage s', "o.item_id=s.id");
        $this->db->where('o.oid', $oid);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $ret["list"] = $query->result_array();
        }

        return $ret;
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