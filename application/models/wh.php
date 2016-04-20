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
    	$filter = $this->input->get('filter');
        $page = intval($this->input->get('page'));
        $page_size = intval($this->input->get('page_size'));
        if(0==$page) $page = 1;
        if(0==$page_size) $page_size =10;

        // get storage.id with $item_sku
        $this->db->select("s.id,oid,`no`,item_sku,item_left,box_num,box_capacity,o.created");
        $this->db->from('storage as s');
        $this->db->join('in_orders o', "s.oid=o.id");

        // in orders no
        if(!empty($filter["pono"])){
            $this->db->like('o.no', trim($filter["pono"]));
        }

        if(!empty($filter["sku"])){
            $this->db->like('s.item_sku', trim($filter["sku"]));
        }

        if(!empty($filter["date"])){
            $time = strtotime(trim($filter["date"]));
            $this->db->where('s.created >=', $time);
            $this->db->where('s.created <=', $time+3600*24);
        }

        $db_total = clone($this->db);
        $total = $db_total->count_all_results();
        $total_page = ceil(floatval($total/$page_size));

        $this->db->order_by("`no` asc, item_sku asc");
        $this->db->limit( $page_size, ($page - 1) * $page_size);
        $query = $this->db->get();

        $list = array();
        if($total > 0){
            foreach ($query->result_array() as $row){
                $row['detail'] = $this->get_item_out_detail($row['id']);
                $row["created"] = strftime("%Y-%m-%d", $row["created"]);
                $list[] = $row;
            }
        }

        return array("page"=> $page, "total"=> $total, "total_page"=> $total_page, "list" => $list, "filter" => $filter );
    }

    function get_item_out_detail($id){
        $sql = "SELECT oid,no,item_id,quantity,item_from,item_to,remark,FROM_UNIXTIME(created, '%c/%e/%Y') as created "
            ."FROM out_order_detail d, out_orders o "
            ."where item_id=? and o.id=d.oid";

        $query = $this->db->query($sql, array($id) );

        return $query->result_array();
    }


    // load sku for typehead
    function sku_list(){
        $sku_str = $this->input->get('sku_str');

        $this->db->distinct("item_sku");
        $this->db->from("storage");
        $this->db->like("item_sku", $sku_str);
        $this->db->limit(50);
        $this->db->order_by("created","desc");

        $query = $this->db->get();

        $ret = array();
        foreach ($query->result_array() as $row) {
            $ret[] = $row["item_sku"]; 
        }

        return $ret;
    }
}