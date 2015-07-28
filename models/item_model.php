<?php

Class Item_model extends CI_Model 
{
	public function get($id)
	{
		$sql = "select 		gs.*, sum(d.quantity) as stock, cs.quantity as cart_stock, 
							(sum(d.quantity) - ifnull(cs.quantity,0)) as onhand
				from 		goods_services gs
				left join	details d
				on 			gs.id = d.good_service_id 
				left join 	(select gs_id, sum(quantity) as quantity from cart_stock GROUP by gs_id) cs
				on 			cs.gs_id = gs.id
				where 		gs.id = ?
				group by 	gs.id";
		$item = $this->db->query($sql,array($id))->row();
		return $item;
	}
	
	public function get_all()
	{
		$items = $this->db->get('goods_services');
		return $items;	
	}
	
	public function get_by_class($class)
	{
		$sql = "select 		gs.*, sum(d.quantity) as stock, cs.quantity as cart_stock, 
							(sum(d.quantity) - ifnull(cs.quantity,0)) as onhand
				from 		goods_services gs
				left join	details d
				on 			gs.id = d.good_service_id 
				left join 	(select gs_id, sum(quantity) as quantity from cart_stock GROUP by gs_id) cs
				on 			cs.gs_id = gs.id
				where 		gs.class = ?
				group by 	gs.id";
		$query = $this->db->query($sql,array($class));
		return $query;
	}
}

?>