<?php
namespace Common\Model;
use Think\Model;

class OStoreModel extends Model{

    public function getStore($wh=[]){
        return $this->where($wh)->find();
    }

    public function getStoreList($wh = array()){
        return $this->where($wh)->order('store_id desc')->select();
    }

    public function getStoreArr($wh = array(), $fieldArr = ['storeId', 'courierCompanyName', 'pickCode', 'storeTime', 'pickTime', 'cabinetId'], $formatTime=false){
        $list = $this->getStoreList($wh);
        $arr = [];
        foreach($list as $store) {
            $st = [];
            if(in_array('storeId', $fieldArr))            { $st['storeId'] = $store['store_id'];}
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
                    $elapsedTime = time() - $store['store_time'];
                    if($elapsedTime < 3600) {
                        $st['storeTime'] = floor($elapsedTime/60).' minites ago';
                    } else if($elapsedTime < 86400) {
                        $st['storeTime'] = floor($elapsedTime/3600).' hours ago';
                    } else {
                        $st['storeTime'] = floor($elapsedTime/86400).' days overdue';
                    }
                } else {
                    $st['storeTime'] = date('Y-m-d H:i:s', $store['store_time']);
                }
            }
            if(in_array('pickTime', $fieldArr))           { $st['pickTime'] =  $store['pick_time'] ? date('Y-m-d H:i:s', $store['pick_time']) : '';}
            if(in_array('cabinetId', $fieldArr))          { $st['cabinetId'] = $store['cabinet_id'];}
            $arr[] = $st;
        }
        return $arr;
    }

    public function updateStore($storeId, $data){
        return $this->data($data)->where("store_id=%d", $storeId)->save();
    }

    public function insertStore($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function insertMember($data){
       if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function getBodyList($where = array()){
        return $this->where($where)->select();
    }
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('store_id')->select();
    }
	
	public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
	
    public function getCabinetList($where = array()){
        return $this->where($where)->select();
    }
    
    public function getStoreOrdersList($where = array(),$page,$pagesize){
      return $this
        ->join('left join member to_member on to_member.member_id = o_store.to_member_id')
				->join('left join o_courier on o_courier.courier_id=o_store.courier_id')
				->join('left join o_member_organization on to_member.member_id = o_member_organization.member_id')
				->join('o_organization_cabinet on o_store.cabinet_id = o_organization_cabinet.cabinet_id and o_organization_cabinet.organization_id = o_member_organization.organization_id')
				->join('left join cabinet_box on cabinet_box.box_id = o_store.box_id')
				->join('left join cabinet_box_model on cabinet_box_model.model_id = cabinet_box.box_model_id')
				->join('left join o_courier_company_organization on o_store.courier_id = o_courier_company_organization.courier_id')
				->join('left join o_courier_company on o_courier_company_organization.company_id = o_courier_company.company_id')
        ->distinct(true)->where($where)->order('create_time desc')
        ->field('o_store.*,o_courier.courier_name,o_member_organization.organization_id,o_courier_company.company_name as unit_name,cabinet_box_model.model_name')
        ->page(intval($page).','.intval($pagesize))->select();
    }
    
    public function countStoreOrders($wh = array()){
        return $this
            ->join('left join member to_member on to_member.member_id = o_store.to_member_id')
						->join('left join o_courier on o_courier.courier_id=o_store.courier_id')
						->join('left join o_member_organization on to_member.member_id = o_member_organization.member_id')
						->join('o_organization_cabinet on o_store.cabinet_id = o_organization_cabinet.cabinet_id and o_organization_cabinet.organization_id = o_member_organization.organization_id')
						->join('left join cabinet_box on cabinet_box.box_id = o_store.box_id')
						->join('left join cabinet_box_model on cabinet_box_model.model_id = cabinet_box.box_model_id')
						->join('left join o_courier_company_organization on o_store.courier_id = o_courier_company_organization.courier_id')
						->join('left join o_courier_company on o_courier_company_organization.company_id = o_courier_company.company_id')
        		->where($wh)->count('distinct store_id');
    }
}
