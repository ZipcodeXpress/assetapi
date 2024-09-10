<?php
namespace Common\Model;
use Think\Model;

class CabinetBoxModelModel extends Model{

    public function getModelName($boxModelId) {
        $boxModel = $this->getByModelId($boxModelId);
        return $boxModel['model_name'];
    }

    public function getModelPrice($boxId) {
        $box = D('CabinetBox')->getBox($boxId);
        if($box) {
            $boxModel = $this->getByModelId($box['box_model_id']);
            return $boxModel['model_price'];
        } else {
            return 0;
        }
    }

    public function getBoxModelList($wh) {
        return $this->where($wh)->select();
    }

    public function getBoxModelConf($cabinetId, $isAllocable=true){

        if($cabinetId) {
            $boxModelCountArr = D('CabinetBox')->countByBoxModel($cabinetId);
        }
        
        if($isAllocable === false) {
            $boxModelArr = $this->select();
        } else {
            $boxModelArr = $this->where(['is_allocable'=>1])->select();
        }
        foreach($boxModelArr as $boxModel) {
            $boxModelConf[$boxModel['model_id']] = [
                'boxModelId' => $boxModel['model_id'],
                'boxModelName' => $boxModel['model_name'],
                'boxModelPrice' => floatval($boxModel['model_price']),
                'length' => $boxModel['length'],
                'width' => $boxModel['width'],
                'height' => $boxModel['height'],
                'img' => C('CDN_ADDRESS').'images/boxmodel/'.$boxModel['model_id'].'.png',
            ];
            if($cabinetId) {
                $boxModelConf[$boxModel['model_id']]['boxModelCount'] = isset($boxModelCountArr[$boxModel['model_id']]) ? $boxModelCountArr[$boxModel['model_id']]['count'] : 0;
            }
        }
        return $boxModelConf;
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
        return $this->where($wh)->order('model_id')->select();
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
