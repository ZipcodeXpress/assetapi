<?php
namespace Common\Model;
use Think\Model;

class ZtopencartOrderModel extends Model{

    public function getOrder($wh){
        return $this->where("order_id=%d", $wh)->find();
    }

    /**
    public function getStoreList($wh = array()){
        return $this->where($wh)->order('store_id desc')->select();
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
    **/
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
        return $this->where($wh)->order('id')->select();
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
