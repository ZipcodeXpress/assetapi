<?php
namespace Adminuser\Model;
use Think\Model;

class CabinetBoxModel extends Model{

    public function assignBox($cabinetId, $boxModelId, $updSts=True){
        $availBoxList = $this->where([
            'cabinet_id' => $cabinetId,
            'box_model_id' => $boxModelId,
            'blocked' => 0,
            'status' => C('z_box_status.available'),
        ])->select();
        $box = $availBoxList[array_rand($availBoxList)];

        if($box) {
            if($updSts) {
                if($this->where(['box_id'=>$box['box_id']])->data([
                    'status' => C('z_box_status.occupied'),
                ])->save()) {
                    return $box['box_id'];
                } else {
                    return false;
                }
            }
            return $box['box_id'];
        } else {
            return $false;
        }
    }

    public function assignCabinetAndBox($zipcode, $boxModelId, $updSts=True){

        $availCabinetList = D('Cabinet')->where([
            'zipcode' => $zipcode,
        ])->select();

        foreach($availCabinetList as $cabinet) {

            $cabinetId = $cabinet['cabinet_id'];

            $availBoxList = $this->where([
                'cabinet_id' => $cabinetId,
                'box_model_id' => $boxModelId,
                'blocked' => 0,
                'status' => C('z_box_status.available'),
            ])->select();
            $box = $availBoxList[array_rand($availBoxList)];

            if($box) {
                if($updSts) {
                    if($this->where(['box_id'=>$box['box_id']])->data([
                        'status' => C('z_box_status.occupied'),
                    ])->save()) {
                        return [$cabinetId, $box['box_id']];
                    } else {
                        return false;
                    }
                }
                return [$cabinetId, $box['box_id']];
            } else {
                return $false;
            }
        }
    }

    public function getBox($boxId, $cabinetId){
        $wh = [
            'box_id' => $boxId,
        ];
        if($cabinetId) {
            $wh['cabinet_id'] = $cabinetId;
        }
        return $this->where($wh)->find();
    }

    public function getBodyBox($boxId){
        return $this
            ->field('cabinet_body.addr as lock_addr, cabinet_body.sequence as body_sequence, cabinet_box.addr as box_addr, cabinet_box_model.is_allocable as is_allocable, cabinet_box_model.model_name as box_model_name, cabinet_box.*')
            ->join('cabinet_body      on cabinet_body.body_id       = cabinet_box.body_id')
            ->join('cabinet_box_model on cabinet_box_model.model_id = cabinet_box.box_model_id')
            ->where("box_id=%d", $boxId)
            ->find();
    }

    public function releaseBox($boxId, $cabinetId){
        $box = $this->getBox($boxId, $cabinetId);
        if($box['status'] == 1) {
            return $this->data([
                'status'=>0,
                'blocked'=>0,
            ])->where("box_id=%d", $boxId)->save();
        } else {
            return false;
        }
    }

    public function occupyBox($boxId){
        $box = $this->getBox($boxId);
        if($box['status'] == 0) {
            return $this->data(['status'=>1])->where("box_id=%d", $boxId)->save();
        } else {
            return false;
        }
    }

    public function blockBox($boxId, $cabinetId){
        $box = $this->getBox($boxId, $cabinetId);
        if($box && $box['blocked'] == 0) {
            return $this->data(['blocked'=>1])->where("box_id=%d", $boxId)->save();
        } else {
            return false;
        }
    }
    public function releaseblockBox($boxId, $cabinetId){
        $box = $this->getBox($boxId, $cabinetId);
        if($box && $box['blocked'] == 1) {
            return $this->data(['blocked'=>0])->where("box_id=%d", $boxId)->save();
        } else {
            return false;
        }
    }
    public function updateBox($boxId, $data){
        return $this->data($data)->where("box_id=%d", $boxId)->save();
    }

    public function insertBox($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getBoxList($where = array()){
        return $this->where($where)->select();
    }

    public function countByBoxModel($cabinetId, $available=true){
        $wh = ['cabinet_id'=>$cabinetId];
        if($available) {
            $wh['status'] = 0;
            $wh['blocked'] = 0;
        }
        $countArr =  $this->field('box_model_id, model_name AS model, count(box_model_id) AS count')->join('__CABINET_BOX_MODEL__ ON __CABINET_BOX_MODEL__.model_id = __CABINET_BOX__.box_model_id')->group('box_model_id')
            ->where($wh)->select();
        $boxModelCountArr = [];
        foreach($countArr as $count) {
            $boxModelCountArr[$count['box_model_id']] = intval($count['count']);
        }

        $boxModelArr = D('CabinetBoxModel')->getBoxModelList([
            'is_allocable'=>1,
            'cabinet_id' => $cabinetId,
        ]);
        foreach($boxModelArr as $k => $boxModel) {
            //$boxModelArr[$k] = [
            $newBoxModelArr[$boxModel['model_id']] = [
                'boxModelId' => $boxModel['model_id'],
                'boxModelName' => $boxModel['model_name'],
                'count' => isset($boxModelCountArr[$boxModel['model_id']]) ? $boxModelCountArr[$boxModel['model_id']] : 0,
            ];
        }
        return $newBoxModelArr;
    }

    public function updateBoxStatus($wh, $status=0) {
        return $this->where($wh)->data([
            'status' => $status,
        ])->save();
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
        return $this->where($wh)->order('box_id')->select();
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
