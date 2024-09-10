<?php
namespace Common\Model;
use Think\Model;

class CargoTypeModel extends Model{

    public function getCargoTypeConf(){
        $cargoTypeArr = $this->select();
        foreach($cargoTypeArr as $cargoType) {
            $cargoTypeConf[$cargoType['cargo_type_id']] = [
                'cargoTypeId' => $cargoType['cargo_type_id'],
                'cargoTypeName' => $cargoType['cargo_type_name'],
                'cargoTypePrice' => floatval($cargoType['cargo_type_price']),
            ];
        }
        return $cargoTypeConf;
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
        return $this->where($wh)->order('cargo_type_id')->select();
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
