<?php
namespace Adminuser\Model;
use Think\Model;

class MemberStoreModel extends Model{

    public function getProfileById($memberId, $field='*'){
        return $this->field($field)->where(array('member_id'=>$memberId))->find();
    }

    public function insertMemberStore($data){
        $data = array_filter($data);
        $data['create_time'] = time();
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function updateMemberStore($Id, $data){
        return $this->where(array('id'=>$Id))->data($data)->save();
    }
    
    public function getBypackCode($map, $field='*'){
        return $this->field($field)->where($map)->find();
    }

	  public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberStoreList($wh){
        return $this->where($wh)->order('create_time desc')->select();
    }
	
	  public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
	
}

