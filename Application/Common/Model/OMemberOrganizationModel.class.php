<?php
namespace Common\Model;
use Think\Model;

class OMemberOrganizationModel extends Model{
    protected $trueTableName = 'o_member_organization';
    public function insertMemberApartment($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function cancelMemberApartment($memberId, $apartmentId){
        $now = time();
        $this->where([
            'member_id' => $memberId,
            'organization_id' => $apartmentId,
            'status'=> ['lt', 3],
        ])->data([
            'status'=> 3,
            'cancel_time' => $now,
        ])->save();
    }

    public function getMember($memberId, $cabinetId=NULL, $needApprove=true){
        $wh = ['o_member_organization.member_id' => $memberId];
        if($cabinetId) {
            $wh = array_merge($wh, [
                'o_organization_cabinet.cabinet_id' => $cabinetId,
            ]);
        }

        if($needApprove) {
            $wh = array_merge($wh, [
                'o_member_organization.status' => 1,
                'o_member_organization.approve_status' => 1,
            ]);
        }
        $mem = $this
            ->field('
                o_member_organization.*,
                member_profile.*,
                member.phone as phone,
                member.email as email,
                o_organization.*,
                o_unit.*,
                o_member_organization.unit_id as unit_id,
                o_member_organization.price as price
            ')
            ->join('member_profile on member_profile.member_id = o_member_organization.member_id')
            ->join('member on member.member_id = o_member_organization.member_id')
            ->join('o_organization on o_member_organization.organization_id = o_organization.organization_id')
            ->join('o_organization_cabinet on o_member_organization.organization_id = o_organization_cabinet.organization_id')
            ->join('o_unit on o_member_organization.unit_id = o_unit.unit_id')
            ->where($wh)->find();
        return $this->formatUnitName($mem);
    }

    public function hasBind($memberId, $apartmentId){
        return $this->where([
            'member_id' => $memberId,
            'organization_id' => $apartmentId,
            'status' => ['neq', 3],
            //'cancel_time' => ['exp', 'is null'],
            'cancel_time' => 0,
        ])->count() > 0;
    }

    public function getMemberApartmentList($wh = array()){
        $list = $this->field('
            o_member_organization.*,
            member_profile.*,
            o_organization.*,
            o_unit.*,
            o_member_organization.unit_id as unit_id,
            o_member_organization.price as price
            ')
            ->join('member_profile on member_profile.member_id = o_member_organization.member_id')
            ->join('o_organization on o_member_organization.organization_id = o_organization.organization_id')
            ->join('o_unit on o_member_organization.unit_id = o_unit.unit_id')
            ->where($wh)->select();

        $arr = [];
        foreach($list as $m) {
            $arr[] = $this->formatUnitName($m);
        }
        return $arr;
    }
	//ASSET 用
     public function getMemberApartmentListN($wh = array()){
        $list = $this->field('
            o_member_organization.*,
            member_profile.*,
            o_organization.*,
            o_member_organization.price as price
            ')
            ->join('member_profile on member_profile.member_id = o_member_organization.member_id')
            ->join('o_organization on o_member_organization.organization_id = o_organization.organization_id')
			//->join('o_unit on o_member_organization.unit_id = o_unit.unit_id')
            ->where($wh)->select();

        $arr = [];
        foreach($list as $m) {
            $arr[] = $this->formatUnitName($m);
        }
        return $arr;
    }   
    public function getMemberApartmentListDomain($wh = array()){
        $list = $this->field('
            o_member_organization.*,
            member_profile.*,
            o_organization.*,
            o_member_organization.unit_id as unit_id,
            o_member_organization.price as price
            ')
            ->join('member_profile on member_profile.member_id = o_member_organization.member_id')
            ->join('o_organization on o_member_organization.organization_id = o_organization.organization_id')
            ->where($wh)->select();


        return $list;
    }

    private function formatUnitName($m) {

        $unitIdArr = explode(',', $m['unit_id']);
        if(sizeof($unitIdArr) > 1) {
            $unitNameArr = [];
            foreach($unitIdArr as $unitId) {
                $unitNameArr[] = D('OUnit')->getUnitName($unitId);
            }
            $m['unit_name'] = implode(',', $unitNameArr);
        }
        return $m;
    }

    /** 
     * o_member_organization.charge_day 1 ~ 31
     * o_member_organization.last_charge_time

        //如果上一次charge的时间在今天之前
        if last_charge_time < (time() - time()%86400 - 1)
            //一个月的最后一天
            if date('j') == date('t')
                charge now
            //非一个月的最后一天，但是 charge_day
            else if date('j') == charge_day
                charge now

     */
    public function getToChargeList() {

        $now = time();
        $todayDay = date('j');
        $monthDayCount = date('t');
        
        $wh = [
            'last_charge_time' => [
                ['lt', $now - $now/86400 - 1],
                ['exp', 'is null'],
                'or'
            ],
            'status' => ['neq', 3],
            'cancel_time' => 0,
        ];

        if($todayDay === $monthDayCount) {
            $wh['charge_day'] = ['gte', $todayDay];
        } else {
            $wh['charge_day'] = $todayDay;
        }

        return $this->where($wh)->select();
    }   

    public function updateMemberApartment($wh, $upd) {
        return $this->where($wh)->data($upd)->save();
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
	//public function getMember($wh){
    //    return $this->where($wh)->find();
    //}
    public function getMemberList($wh){
        return $this->where($wh)->order('member_id')->select();
    }
	
	 public function getMemberListN($wh){
        return $this->where($wh)->order('member_id')->find();
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
