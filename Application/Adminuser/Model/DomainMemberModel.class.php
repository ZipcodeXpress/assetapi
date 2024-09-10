<?php
namespace Adminuser\Model;
use Think\Model;

class DomainMemberModel extends Model{

    public function getDomainMember($cabinetId){
        return $this->where("cabinet_id=%d", $cabinetId)->find();
    }

    public function getDomainAddressByCabinetId($cabinetId){
        $ap = $this->where("cabinet_id=%d", $cabinetId)->find();
        $ap = D('Domain')->getDomainInfo($ap['domain_id']);
        return $ap['address'];
    }

    public function updateDomainMember($cabinetId, $data){
        return $this->data($data)->where("cabinet_id=%d", $cabinetId)->save();
    }

    public function insertDomainMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getDomainMemberList($wh = array(),$page,$pagesize){
        return $this
            ->join('left join member on member.member_id = domain_member.member_id')
            ->join('left join domain on domain.domain_id = domain_member.domain_id')
            ->where($wh)->field('domain_member.domain_id,domain_member.approve_time,domain_member.approve_status,domain_member.status,domain_member.member_id,member.email,member.phone,domain.domain_name')->page(intval($page).','.intval($pagesize))->select();
    }
    
    public function countDomainMember($wh = array()){
        return $this
            ->where($wh)->count();
    }

    public function getDomainMemberArr($wh = array(), $fieldArr = array()){
        $list = $this->getDomainMemberList($wh);
        foreach($list as $k => $c) {
            $cb = [];
            if(in_array('cabinetId', $fieldArr))        { $cb['cabinetId'] = $c['cabinet_id'];}
            if(in_array('latitude', $fieldArr))         { $cb['latitude'] = $c['latitude'];}
            if(in_array('longitude', $fieldArr))         { $cb['longitude'] = $c['longitude'];}
            if(in_array('address', $fieldArr))         { $cb['address'] = $c['address'];}
            if(in_array('address_url', $fieldArr))         { $cb['addressUrl'] = $c['address_url'];}
            $arr[] = $cb;
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
        return $this->where($wh)->order('domain_id')->select();
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
