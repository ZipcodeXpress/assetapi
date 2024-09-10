<?php
namespace Adminuser\Model;
use Think\Model;

class MemberCabinetModel extends Model{

    public function getMemberCabinet($memberId){
        return $this->where("member_id=%d", $memberId)->find();
    }

    public function updateMemberCabinet($data){
        $memberId = $data['member_id'];
        unset($data['member_id']);
        return $this->data($data)->where("member_id=%d", $memberId)->save();
    }

    public function insertMemberCabinet($data){
        if($this->where($data)->find()) {
            return true;
        } else if($this->getMemberCabinet($data['member_id'])) {
            return $this->updateMemberCabinet($data);
        }
        $data['create_time'] = time();
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
        return $this->where($wh)->order('member_id')->select();
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
