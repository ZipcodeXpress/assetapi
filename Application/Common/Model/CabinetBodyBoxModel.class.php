<?php
namespace Common\Model;
use Think\Model;

class CabinetBodyBoxModel extends Model{

    public function getBodyBox($bodyBoxId){
        return $this->where("body_box_id=%d", $bodyBoxId)->find();
    }

    public function updateBodyBox($bodyBoxId, $data){
        return $this->data($data)->where("body_box_id=%d", $bodyBoxId)->save();
    }

    public function insertBodyBox($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getBodyBoxList($where = array()){
        return $this->where($where)->select();
    }
	
    public function insertMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('body_box_id')->select();
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
