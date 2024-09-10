<?php
namespace Adminuser\Model;
use Think\Model;

class OApartmentCabinetModel extends Model{

    public function getApartmentCabinet($cabinetId){
        return $this->where("cabinet_id=%d", $cabinetId)->find();
    }

    public function getApartmentAddressByCabinetId($cabinetId){
        $ap = $this->where("cabinet_id=%d", $cabinetId)->find();
        $ap = D('OApartment')->getApartmentInfo($ap['apartment_id']);
        return $ap['address'];
    }

    public function updateApartmentCabinet($cabinetId, $data){
        return $this->data($data)->where("cabinet_id=%d", $cabinetId)->save();
    }

    public function insertApartmentCabinet($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getApartmentCabinetList($wh = array()){
        return $this
            ->join('cabinet on cabinet.cabinet_id = o_apartment_cabinet.cabinet_id')
            ->where($wh)->select();
    }

    public function getApartmentCabinetArr($wh = array(), $fieldArr = array()){
        $list = $this->getApartmentCabinetList($wh);
        foreach($list as $k => $c) {
            $cb = [];
            if(in_array('cabinetId', $fieldArr))        { $cb['cabinetId'] = $c['cabinet_id'];}
            if(in_array('latitude', $fieldArr))         { $cb['latitude'] = $c['latitude'];}
            if(in_array('longitude', $fieldArr))         { $cb['longitude'] = $c['longitude'];}
            if(in_array('address', $fieldArr))         { $cb['address'] = $c['address'];}
            if(in_array('address_url', $fieldArr))         { $cb['addressUrl'] = $c['address_url'];}
            $arr[] = $cb;
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
