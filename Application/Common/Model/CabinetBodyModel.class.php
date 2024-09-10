<?php
namespace Common\Model;
use Think\Model;

class CabinetBodyModel extends Model{

    public function getBody($bodyId){
        return $this->where("body_id=%d", $bodyId)->find();
    }

    public function updateBody($bodyId, $data){
        return $this->data($data)->where("body_id=%d", $bodyId)->save();
    }

    public function updateBodySequence($cabinetId, $addr, $seq){
        if(!isset($cabinetId)) return false;
        if(!isset($addr)) return false;
        if(!isset($seq)) return false;
        return $this->data(['sequence'=>$seq])->where([
            'cabinet_id' => $cabinetId,
            'addr' => $addr,
        ])->save();
    }
    public function getBoxModelList($cabinetId)
	{
        return $this->where("cabinet_id=%d", $cabinetId)->select();
    }
    public function insertBody($data){
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
        return $this->where($wh)->order('body_id')->select();
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
