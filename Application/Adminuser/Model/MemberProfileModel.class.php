<?php
namespace Adminuser\Model;
use Think\Model;

class MemberProfileModel extends Model{

    public function getProfile($memberId){
        return $this->where(array('member_id'=>$memberId))->find();
    }

    public function getMemberName($memberId){
        $member = $this->where(array('member_id'=>$memberId))->find();
        return $member['first_name'].' '.$member['last_name'];
    }

    public function getProfileById($memberId, $field='*'){
        return $this->field($field)->where(array('member_id'=>$memberId))->find();
    }

    public function insertProfile($memberId, $data){
        $data = array_filter($data);
        $data['create_time'] = time();
        $data['member_id'] = $memberId;
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function updateProfile($memberId, $data){
        return $this->where(array('member_id'=>$memberId))->data($data)->save();
    }

    public function isProfileCompleted($memberId){
        $profile = $this->getProfile($memberId);
        unset($profile['householder_member']);
        unset($profile['addressline2']);
        unset($profile['interest']);
        unset($profile['phone']);//phone not used, use member.phone
        unset($profile['sex']);
        unset($profile['avatar']);
        unset($profile['profile_id']);
        return sizeof(array_filter($profile)) == sizeof($profile) ? 1 : 0;
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
        return $this->where($wh)->order('member_id')->select();
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

