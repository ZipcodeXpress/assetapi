<?php
namespace Adminuser\Model;
use Think\Model;

class OApartmentModel extends Model{

    public function getApartment($apartmentId){
        return $this->where("apartment_id=%d", $apartmentId)->find();
    }

    public function getApartmentInfo($apartmentId) {
        $ap = $this->getApartment($apartmentId);
        if(empty($ap)) {
            return false;
        }
        return $this->formatApartment($ap);
    }

    private function formatApartment($apartment){
        return [
            'apartmentId' => $apartment['apartment_id'],
            'apartmentName' => $apartment['apartment_name'],
            'address' => $apartment['address'],
        ];
    }

    public function updateApartment($apartmentId, $data){
        return $this->data($data)->where("apartment_id=%d", $apartmentId)->save();
    }

    public function insertApartment($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getApartmentList($wh = array()){
        return $this->where($wh)->select();
    }

    public function getApartmentArr($wh = array(), $field = array()){
        $list = $this->getApartmentList($wh);
        foreach($list as $k => $c) {
            $arr[$c['apartment_id']] = $this->formatApartment($c);
        }
        return $arr;
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
        return $this->where($wh)->order('apartment_id')->select();
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
