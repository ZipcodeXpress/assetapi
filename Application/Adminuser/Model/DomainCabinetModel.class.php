<?php
namespace Adminuser\Model;
use Think\Model;

class DomainCabinetModel extends Model{

    public function getDomainCabinet($cabinetId){
        return $this->where("cabinet_id=%d", $cabinetId)->find();
    }

    public function getDomainAddressByCabinetId($cabinetId){
        $ap = $this->where("cabinet_id=%d", $cabinetId)->find();
        $ap = D('Domain')->getDomainInfo($ap['domain_id']);
        return $ap['address'];
    }

    public function updateDomainCabinet($cabinetId, $data){
        return $this->data($data)->where("cabinet_id=%d", $cabinetId)->save();
    }

    public function insertDomainCabinet($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getDomainCabinetList($wh = array(),$page,$pagesize){
        return $this
            ->join('cabinet on cabinet.cabinet_id = domain_cabinet.cabinet_id')
            ->join('domain on domain.domain_id = domain_cabinet.domain_id')
            ->where($wh)->field('domain_cabinet.domain_id,domain_cabinet.cabinet_id,cabinet.address,domain_cabinet.create_time,domain.domain_name')->page(intval($page).','.intval($pagesize))->select();
    }
    
    public function countDomainCabinet($wh = array()){
        return $this
            ->where($wh)->count();
    }

    public function getDomainCabinetArr($wh = array(), $fieldArr = array()){
        $list = $this->getDomainCabinetList($wh);
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
