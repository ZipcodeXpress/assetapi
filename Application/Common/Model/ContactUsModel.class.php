<?php
namespace Common\Model;
use Think\Model;

class ContactUsModel extends Model{

    public function getFromConf() {
        $list = $this->distinct(true)->field('from')->select();
        $arr = [];
        foreach($list as $from) {
            $arr[] = $from['from'];
        }
        return $arr;
    }

    public function getContactUsList($where) {
        $list = $this->where($where)->select();
        foreach($list as $k => $from) {
            $list[$k]['create_time'] = date('Y-m-d H:i:s', $from['create_time']);
        }
        return $list;
    }

    public function insertContactUs($params){
        $data = array(
            'first_name' => $params['firstName'],
            'last_name' => $params['lastName'],
            'company' => $params['company'],
            'email' => $params['email'],
            'phone' => $params['phone'],
            'city' => $params['city'],
            'zip' => $params['zip'],
            'call_time' => $params['callTime'],
            'comments' => $params['comments'],
            'from' => $params['from'],
            'create_time'=>time()
        );
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
        return $this->where($wh)->order('contact_id')->select();
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
