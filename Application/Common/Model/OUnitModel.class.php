<?php
namespace Common\Model;
use Think\Model;

class OUnitModel extends Model{

    public function getUnit($unitId){
        return $this->where("unit_id=%d", $unitId)->find();
    }

    public function getUnitName($unitId){
        $unit = $this->where("unit_id=%d", $unitId)->find();
        return $unit['unit_name'];
    }

    public function updateUnit($unitId, $data){
        return $this->data($data)->where("unit_id=%d", $unitId)->save();
    }

    public function insertUnit($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getUnitList($wh = array()){
        return $this->where($wh)->select();
    }

    public function getUnitArr($wh = array()){
        $list = $this->getUnitList($wh);
        foreach($list as $k => $c) {
            $arr[$c['unit_id']] = [
                'unitId' => $c['unit_id'],
                'unitName' => $c['unit_name'],
                //'createTime' => date('Y-m-d H:i:s', $c['create_time']),
            ];
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
        return $this->where($wh)->order('unit_id')->select();
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
