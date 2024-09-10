<?php
namespace Common\Model;
use Think\Model;

class CabinetModel extends Model{

    public function getCabinet($cabinetId){
        return $this->where("cabinet_id=%d", $cabinetId)->find();
    }

    public function getCabinetAddress($cabinetId){
        $cabinet = $this->where("cabinet_id=%d", $cabinetId)->find();
        return $cabinet['address'];
    }

    public function getCabinetAddressUrl($cabinetId){
        $cabinet = $this->where("cabinet_id=%d", $cabinetId)->find();
        return $cabinet['address_url'];
    }

    public function getZipcodeByCabinetId($cabinetId){
        $cabinet = $this->where("cabinet_id=%s", $cabinetId)->find();
        return $cabinet ? $cabinet['zipcode'] : null;
    }

    public function getCabinetByZipcode($zipcode){
        return $this->where("zipcode=%s", $zipcode)->find();
    }
    
    public function getCabinetsByZipcode($zipcode){
        return $this->where($zipcode)->field('cabinet_id')->select();
    }

    public function updateCabinet($cabinetId, $data){
        return $this->data($data)->where("cabinet_id=%d", $cabinetId)->save();
    }

    public function insertCabinet($data){
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
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('cabinet_id')->select();
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
	

	
    public function getCabinetArr($where = array()){
        $list = $this->getCabinetList();
        foreach($list as $k => $c) {
            $arr[$c['cabinet_id']] = $c;
        }
        return $arr;
    }

    public function getDistance($fromCabinetId, $toCabinetId) {
        if(empty($toCabinetId) || $fromCabinetId == $toCabinetId) {
            return 0;
        }
        $from = $this->getCabinet($fromCabinetId);
        $to   = $this->getCabinet($toCabinetId);

        $Util = new \Common\Common\Util();
        return $Util->getDistance($from['latitude'], $from['longitude'], $to['latitude'], $to['longitude']);
    }
}
