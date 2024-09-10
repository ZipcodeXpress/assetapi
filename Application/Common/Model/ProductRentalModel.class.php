<?php
namespace Common\Model;
use Think\Model;

class ProductRentalModel extends Model{
	protected $trueTableName = 'product_rental';
	//
	public function getRental($wh=[]){
        return $this->where($wh)->find();
    }
	
    public function updateStore($storeId, $data){
        return $this->data($data)->where("rental_id=%d", $storeId)->save();
    } 
	
    public function getRentalList($wh = array()){
        return $this->where($wh)->order('rental_id desc')->select();
    }
	//
    public function getRentalArr($wh = array(), $fieldArr = ['storeId', 'courierCompanyName', 'pickCode', 'storeTime', 'pickTime', 'cabinetId'], $formatTime=false){
        $list = $this->getStoreList($wh);
        $arr = [];
        foreach($list as $store) {
            $st = [];
            if(in_array('storeId', $fieldArr))            { $st['storeId'] = $store['rental_id'];}
            if(in_array('courierCompanyName', $fieldArr)) {
                if($store['courier_id']) {
                    $courier = D('OCourier')->getCourier($store['courier_id']);
                    if($courier) {
                        $st['courierCompanyName'] = $courier['company_name'];
                    }
                }
            }
            if(in_array('address', $fieldArr)) {
                if($store['cabinet_id']) {
                    $st['address'] = D('OOrganizationCabinet')->getApartmentAddressByCabinetId($store['cabinet_id']);
                }
            }
            if(in_array('pickCode', $fieldArr))           { $st['pickCode'] = $store['pick_code'];}
            if(in_array('storeTime', $fieldArr))          {
                if($formatTime) {
                    $elapsedTime = time() - $store['rental_time'];
                    if($elapsedTime < 3600) {
                        $st['storeTime'] = floor($elapsedTime/60).' minites ago';
                    } else if($elapsedTime < 86400) {
                        $st['storeTime'] = floor($elapsedTime/3600).' hours ago';
                    } else {
                        $st['storeTime'] = floor($elapsedTime/86400).' days overdue';
                    }
                } else {
                    $st['storeTime'] = date('Y-m-d H:i:s', $store['rental_time']);
                }
            }
            if(in_array('pickTime', $fieldArr))           { $st['pickTime'] =  $store['return_time'] ? date('Y-m-d H:i:s', $store['pick_time']) : '';}
            if(in_array('cabinetId', $fieldArr))          { $st['cabinetId'] = $store['cabinet_id'];}
            $arr[] = $st;
        }
        return $arr;
    }
	//租借列表
    public function getStatementList($memberId, $page=NULL, $startDate=NULL, $endDate=NULL){
        $whereStr = 't.member_id=%d';
        if($startDate && $endDate) {
            $whereStr .= sprintf(' AND t.rental_time > %s AND t.rental_time < %s', $startDate, $endDate);
        }
		$mem = $this
		    ->alias('t') 
            ->field('
                t.rental_id,
                o.product_name as product_name,
			    o.brand as brand,
				t.cabinet_id,
                t.box_id,
				t.rental_time,
				t.return_time
            ')
			->join('left join product_inventory as p on t.product_inventory_id = p.product_inventory_id')
            ->join('left join product as o on p.product_id = o.product_id')
            ->where($whereStr, $memberId)->select();
		return $mem;
		//
        //return $this->field('rental_id,member_id,organization_id,cabinet_id,product_inventory_id,box_id,rental_time')->where($whereStr, $memberId)->order('rental_time desc, rental_id desc')->select();
    }
	//预定列表
    public function getStatementListNN($wh = array()){
        //$whereStr = 't.member_id=%d AND rental_status_code=\'2\'';
        //if($startDate && $endDate) {
        //    $whereStr .= sprintf(' AND t.reserve_time > %s AND t.reserve_time < %s', $startDate, $endDate);
        //}
		$mem = $this
		    ->alias('t') 
            ->field('
                t.rental_id,
                o.product_name as product_name,
				t.pickup_code,
			    o.brand as brand,
				t.cabinet_id,
                t.box_id,
				t.reserve_time
            ')
			->join('left join product_inventory as p on t.product_inventory_id = p.product_inventory_id')
            ->join('left join product as o on p.product_id = o.product_id')
            ->where($wh)->select();
		return $mem;
		//
        //return $this->field('rental_id,member_id,organization_id,cabinet_id,product_inventory_id,box_id,rental_time')->where($whereStr, $memberId)->order('rental_time desc, rental_id desc')->select();
    }
		//租借列表
    public function getStatementListN($wh = array()){
		$mem = $this
		    ->alias('t') 
            ->field('
                t.rental_id,
                o.product_name as product_name,
			    o.brand as brand,
				t.cabinet_id,
                t.box_id,
				t.rental_time
            ')
			->join('left join product_inventory as p on t.product_inventory_id = p.product_inventory_id')
            ->join('left join product as o on p.product_id = o.product_id')
            ->where($whereStr, $memberId)->select();
		return $mem;
		//
     }
    //插入记录
    public function insertMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
	//得到记录（1条）
	public function getMember($wh){
        return $this->where($wh)->find();
    }
	//得到记录（多条）
	public function getList($where = array())
	{
        return $this->where($where)->select();
    }
	//得到记录（多条关键字检索）
    public function getMemberList($wh){
        return $this->where($wh)->order('rental_id')->select();
    }
	//删除记录
	public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	//更新记录
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
		
}
