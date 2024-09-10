<?php
namespace Adminuser\Model;
use Think\Model;

class OCourierModel extends Model{

    public function getCourier($courierId){
        return $this
            ->join('o_courier_company on o_courier_company.company_id = o_courier.company_id')
            ->where("courier_id=%d", $courierId)->find();
    }

    public function updateCourier($courierId, $data){
        return $this->data($data)->where("courier_id=%d", $courierId)->save();
    }

    public function insertCourier($data){
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
        return $this->where($wh)->order('courier_id')->select();
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
