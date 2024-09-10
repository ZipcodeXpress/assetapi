<?php
namespace Common\Model;
use Think\Model;

class ZCourierZipcodeModel extends Model{

    public function getCourierZipcodeList($memberId, $type='starting'){
        $zipcodeArr = [];
        foreach($this->field('zipcode')->where([
            'courier_id' => $memberId,
            'type' => $type,
        ])->select() as $zipcode) {
            $zipcodeArr[] = $zipcode['zipcode'];
        }
        return $zipcodeArr;
    }

    public function insertCourierZipcode($data){
        if($this->where($data)->find()) {
            return true;
        }
        $data['create_time'] = time();
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function deleteCourierZipcode($data){
        return $this->where($data)->delete();
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
