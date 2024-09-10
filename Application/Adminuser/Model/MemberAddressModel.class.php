<?php
namespace Adminuser\Model;
use Think\Model;

class MemberAddressModel extends Model{

    public function insertAddress($data){
        $data['create_time'] = time();
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getAddress($memberId) {
        return $this->where(['member_id'=>$memberId])->find();
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
        return $this->where($wh)->order('address_id')->select();
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
