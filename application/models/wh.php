<?php

class Wh extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    // list order
    function load_list()
    {	
    	$filter = $this->input->get('input_no');
        $page = intval($this->input->get('page'));
        $page_size = intval($this->input->get('page_size'));
        if(0==$page) $page = 1;
        if(0==$page_size) $page_size =10;

        // get storage.id with $item_sku
        $this->db->select("id,oid,item_sku,item_left,box_capacity");
        $this->db->from('storage');
        //$this->db->where('item_sku', $item_sku);
        //$this->db->order_by('created','asc');

        $sql = "select s.id,oid,`no`,item_sku,item_left,box_num,box_capacity,FROM_UNIXTIME(o.created, '%Y-%m-%d') as created "
            ."FROM `storage` s, in_orders o "
            ."WHERE s.oid=o.id ";
        $query = $this->db->query($sql);

        $total = $query->num_rows();
        $total_page = ceil(floatval($total/$page_size));

        $list = array();
        if($total > 0){
            foreach ($query->result_array() as $row){
                $row['detail'] = $this->get_item_out_detail($row['id']);
                $list[] = $row;
            }
        }

        return array("page"=> $page, "total"=> $total, "total_page"=> $total_page, "list" => $list );
    }

    function get_item_out_detail($id){
        $sql = "SELECT oid,no,item_id,quantity,item_from,item_to,remark,FROM_UNIXTIME(created, '%b %e, %Y') as created "
            ."FROM out_order_detail d, out_orders o "
            ."where item_id=? and o.id=d.oid";

        $query = $this->db->query($sql, array($id) );

        return $query->result_array();
    }
}