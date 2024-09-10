<?php
namespace Common\Model;
use Think\Model;

class DomainModel extends Model{

    public function getDomain($domainId){
        return $this->where("domain_id=%d", $domainId)->find();
    }

    public function getDomainInfo($domainId) {
        $ap = $this->getDomain($domainId);
        if(empty($ap)) {
            return false;
        }
        return $this->formatDomain($ap);
    }

    private function formatDomain($domain){
        return [
            'domainId' => $domain['domain_id'],
            'domainName' => $domain['domain_name'],
            'address' => $domain['address'],
        ];
    }

    public function updateDomain($domainId, $data){
        return $this->data($data)->where("domain_id=%d", $domainId)->save();
    }

    public function insertDomain($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function getDomainList($wh = array(),$page,$pagesize){
        return $this->where($wh)->page(intval($page).','.intval($pagesize))->select();
    }
    
    public function countDomain($wh = array()){
        return $this
            ->where($wh)->count();
    }

    public function getDomainArr($wh = array(), $field = array()){
        $list = $this->getDomainList($wh);
        foreach($list as $k => $c) {
            $arr[$c['domain_id']] = $this->formatDomain($c);
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
