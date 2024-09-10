<?php
namespace Common\Model;
use Think\Model;

class ZtStoreModel extends Model{

    public function getStore($wh=[]){
        return $this->where($wh)->find();
    }

    public function getStoreList($wh = array()){
        return $this->where($wh)->order('store_id desc')->select();
    }

/**
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
*/

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
}
