<?php
namespace Common\Model;
use Think\Model;

class ORoomModel extends Model{

    public function getRoom($roomId){
        return $this->where("room_id=%d", $roomId)->find();
    }

    public function updateRoom($roomId, $data){
        return $this->data($data)->where("room_id=%d", $roomId)->save();
    }

    public function insertRoom($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getRoomList($wh = array()){
        return $this->where($wh)->select();
    }

    public function getRoomArr($wh = array()){
        $list = $this->getRoomList($wh);
        foreach($list as $k => $c) {
            $arr[$c['room_id']] = [
                'roomId' => $c['room_id'],
                'roomName' => $c['room_name'],
                //'buildingId' => $c['building_id'],
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
        return $this->where($wh)->order('room_id')->select();
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
