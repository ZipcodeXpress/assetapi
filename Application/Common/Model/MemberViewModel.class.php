<?php
namespace Common\Model;
use Think\Model\ViewModel;

class MemberViewModel extends ViewModel{

    public $viewFields = array(
        'Member'=>array('member_id', 'email', 'phone', 'status', 'register_time', 'lastlogin_time', 'is_email_verified', 'is_profile_completed', 'has_credit_card',
           '(select count(*) from card_credit where card_credit.member_id = Member.member_id and card_credit.status = 0)'=>'cc_count',
            '_type'=>'LEFT'
        ),
        'MemberProfile'=>array(
            'nick_name' => 'nick_name',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'householder_member' => 'householder_member',
            'avatar' => 'avatar',
            'sex' => 'sex',
            'addressline1' => 'addressline1',
            'addressline2' => 'addressline2',
            'city' => 'city',
            'state' => 'state',
            'zipcode' => 'zipcode',
            'birth' => 'birth',
            'username' => 'username',
            '_on'=>'MemberProfile.member_id=Member.member_id',
            '_type'=>'LEFT'
        ),
        'Wallet'=>array(
            'money'=>'money',
            'frozen_money'=>'frozen_money',
            'ubi'=>'ubi',
            '_on'=>'Wallet.member_id=Member.member_id',
            '_type'=>'LEFT'
        ),
    );

    public function getMemberView($memberId){
        $member = $this->where('Member.member_id = %d', $memberId)->find();

        return $member;
    }

    public function getMemberListView($wh){
        $memberList = $this->where($wh)->order('member.register_time desc')->select();

        return $memberList;
    }
}
