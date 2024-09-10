<?php
namespace Adminuser\Model;
use Think\Model;

class OBuildingModel extends Model{

    public function getBuilding($buildingId){
        return $this->where("building_id=%d", $buildingId)->find();
    }

    public function getBuildingInfo($buildingId) {
        $bl = $this->getBuilding($buildingId);
        if(empty($bl)) {
            return false;
        }
        return $this->formatBuilding($bl);
    }

    private function formatBuilding($building) {
        return [
            'buildingId' => $building['building_id'],
            'buildingName' => $building['building_name'],
        ];
    }

    public function updateBuilding($buildingId, $data){
        return $this->data($data)->where("building_id=%d", $buildingId)->save();
    }

    public function insertBuilding($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getBuildingList($wh = array()){
        return $this->where($wh)->select();
    }

    public function getBuildingArr($wh = array(), $field = array()){
        $list = $this->getBuildingList($wh);
        foreach($list as $k => $c) {
            $arr[$c['building_id']] = $this->formatBuilding($c);
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
        return $this->where($wh)->order('building_id')->select();
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
