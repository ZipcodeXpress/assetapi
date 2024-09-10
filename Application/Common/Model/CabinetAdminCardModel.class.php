<?php
namespace Common\Model;
use Think\Model;

class CabinetAdminCardModel extends Model{

    public function getAdminCardList($where = array()){
        return $this->where($where)->select();
    }
	public function insertMember($data= array()){
        if ($this->create($data,self::MODEL_INSERT)) {
            return $this->add();
        } else {
            return false;
        }
    }
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('card_id')->select();
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
