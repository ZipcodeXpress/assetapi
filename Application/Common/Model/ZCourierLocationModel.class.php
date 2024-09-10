<?php
namespace Common\Model;
use Think\Model;

class ZCourierLocationModel extends Model{

    public function getCourierLocationList($deliverId){
        $order = D('ZCourierOrder')->getCourierOrder([
            'deliver_id' => $deliverId,
        ]);
        if($order) {
            if($order['fetch_time'] && $order['deliver_time']) {
                return $this->field('longitude, latitude, FROM_UNIXTIME(create_time) as time')->where([
                    'courier_id' => $order['courier_id'],
                    'create_time' => ['between', [$order['fetch_time'], $order['deliver_time']]],
                ])->order('create_time asc')->select();
            } else if($order['fetch_time']) {
                return $this->field('longitude, latitude, FROM_UNIXTIME(create_time) as time')->where([
                    'courier_id' => $order['courier_id'],
                    'create_time' => ['gt', $order['fetch_time']],
                ])->order('create_time asc')->select();
            } else {
            return [];
            }
        } else {
            return [];
        }
    }

    public function insertCourierLocation($data){
        $data['create_time'] = time();
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
        return $this->where($wh)->order('location_id')->select();
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
