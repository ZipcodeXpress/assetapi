<?php
namespace Common\Model;
use Think\Model;

class ZDeliverTraceModel extends Model{

    public function getDeliverTraceList($wh = array()){
        $traceArr = $this->where($wh)->order('create_time desc')->select();
        $traceList = [];
        foreach($traceArr as $key => $trace) {
            $traceList[$key]['text'] = $trace['trace'];
            $traceList[$key]['time'] = date('Y-m-d H:i:s', $trace['create_time']);
        }
        return $traceList;
    }

    public function insertDeliverTrace($deliverId, $trace){
        $data = [
            'deliver_id' => $deliverId,
            'trace' => $trace,
            'desc' => $trace,
            'create_time' => time(),
        ];
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
        return $this->where($wh)->order('trace_id')->select();
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
