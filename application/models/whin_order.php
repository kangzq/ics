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

        return $this->db->trans_status() ? 1 : 0;
    }

    // in order
    function update_order()
    {   
        $this->order_no = $this->input->post('input_no');
        $this->created = time();

        $query = $this->db->query('select id from in_orders where `no`=? and deleted=0',[$this->order_no]);

        $this->order_id = $query->row_array(0)['id'];

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

        return $this->db->affected_rows() ? 1 : 0;
    }

    function get_order($oid)
    {
        $oid = intval($oid);
        if($oid==0) return [];
        // get storage.id with $item_sku
        $this->db->select("s.id,oid,`no`,item_sku,item_left,box_num,box_id,box_capacity,is_packed,pallet_id,o.deleted,o.created");
        $this->db->from('storage as s');
        $this->db->join('in_orders o', "s.oid=o.id");
        $this->db->where('s.oid', $oid);
        $this->db->where('s.deleted', 0);
        $query = $this->db->get();

        return $query->result_array();
    }

    function can_remove_item($item_id)
    {
        $sql = "select count(0) as cnt from out_order_detail where item_id=? limit 1";
        $query = $this->db->query($sql, [$item_id]);

        return ($query->row_array(0)['cnt']>0) ? 0 : 1;
    }

    function can_remove_order($order_id)
    {
        $sql = "select count(0) as cnt from `storage` s, out_order_detail o where s.oid=? and o.item_id = s.id limit 1";
        $query = $this->db->query($sql, [$order_id]);
        return ($query->row_array(0)['cnt']>0) ? 0 : 1;
    }

    function remove_item($item_id)
    {
        $this->db->update('`storage`', ["deleted"=> 1], ["id" => $item_id]);
        return $this->db->affected_rows();
    }

    function remove_order($order_id)
    {
        $this->db->update('`storage`', ["deleted"=> 1], ["oid" => $order_id]);
        $this->db->update('`in_orders`', ["deleted"=> 1], ["id" => $order_id]);
        return $this->db->affected_rows();
    }
}