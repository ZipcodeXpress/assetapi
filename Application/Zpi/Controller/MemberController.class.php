<?php
namespace Zpi\Controller;
use Think\Controller;
use Think\Log\Driver;

class MemberController extends BaseController {

    /**
     * @api {get} /member/getMember getMember
     * @apiName getMember
     * @apiGroup 02-Member
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg

     * @apiSuccess {String} data
     * @apiSuccess {Object} data.member
     * @apiSuccess {String} data.member.memberId
     * @apiSuccess {String} data.member.email
     * @apiSuccess {String} data.member.phone
     * @apiSuccess {String} data.member.status for Web
     * @apiSuccess {String} data.member.statusText
            0 => 'register completed',                  
            2 => 'email has not been verified',                  
            3 => 'profile has not been completed',                  
            4 => 'credit card has not been binded',                  
     * @apiSuccess {Object} data.member.statusDetail
     * @apiSuccess {String} data.member.statusDetail.isEmailVerified     Email是否验证
     * @apiSuccess {String} data.member.statusDetail.isProfileCompleted  是否完善资料（资料全部都填）
     * @apiSuccess {String} data.member.statusDetail.hasCreditCard       是否有绑定信用卡
     * @apiSuccess {String} data.member.statusDetail.hasBindAddress      是否有绑定快递地址
     * @apiSuccess {String} data.member.statusDetail.hasBindCabinet      是否有绑定快递柜
     * @apiSuccess {Object} data.profile
     * @apiSuccess {String} data.profile.nickName
     * @apiSuccess {String} data.profile.firstName
     * @apiSuccess {String} data.profile.lastName
     * @apiSuccess {String} data.profile.householderMember
     * @apiSuccess {String} data.profile.avatar
     * @apiSuccess {String} data.profile.sex
     * @apiSuccess {String} data.profile.addressline1
     * @apiSuccess {String} data.profile.addressline2
     * @apiSuccess {String} data.profile.city
     * @apiSuccess {String} data.profile.state
     * @apiSuccess {String} data.profile.zipcode
     * @apiSuccess {String} data.profile.birth
     * @apiSuccess {Object} data.wallet
     * @apiSuccess {Number} data.wallet.money                   current money: 当前可用金额
     * @apiSuccess {Number} data.wallet.frozenMoney             current frozen money: 当前冻结金额
     * @apiSuccess {Number} data.wallet.ubi                     current ubi amount: 当前优币量
     * @apiSuccess {Object} [data.courier] 快递员信息
     * @apiSuccess {String} data.courier.userRating 用户评分

     * @apiSampleRequest
     */
    public function getMember(){

        $member = D('MemberView')->getMemberView($this->_memberId);

        $regStatusText = C('member.reg_status_text');
        $data = [
            'member' => [
                'memberId' => $member['member_id'],
                'email' => $member['email'],
                'phone' => $member['phone'],
                'status' => $member['status'],
                'statusText' => $regStatusText[$member['status']],
                'statusDetail'         => [
                    'isEmailVerified'      => $member['is_email_verified'],
                    'isProfileCompleted'   => D('MemberProfile')->isProfileCompleted($member['member_id']),
                    'hasCreditCard'        => $member['has_credit_card'],
                    'hasBindAddress'       => D('MemberAddress')->getAddress($member['member_id']) ? 1: 0,
                    'hasBindCabinet'       => D('MemberCabinet')->getMemberCabinet($member['member_id']) ? 1: 0,
                ],
            ],
            'profile' => [
                'nickName' => $member['nick_name'],
                'firstName' => $member['first_name'],
                'lastName' => $member['last_name'],
                'householderMember' => $member['householder_member'] ? : '',
                'avatar' => $member['avatar'] ? C('CDN_ADDRESS').$member['avatar'] : '',
                'sex' => $member['sex'],
                'addressline1' => $member['addressline1'],
                'addressline2' => $member['addressline2'],
                'city' => $member['city'],
                'state' => D('State')->getByStateCode($member['state'])['state'],
                'zipcode' => $member['zipcode'],
                'birth' => $member['birth'],
                'username' => $member['username'],
            ],
            'wallet' => [
                'money' => $member['money'],
                'frozenMoney' => $member['frozen_money'],
                'ubi' => $member['ubi'],
            ],
        ];

        $courier = D('ZCourier')->getByCourierId($this->_memberId);
            $data['courier'] = [
                'userRating' => $courier ? $courier['user_rating'] : 0,
            ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /member/switchServiceMode switchServiceMode
     * @apiName switchServiceMode
     * @apiGroup 02-Member
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} serviceMode     eg. ziplocker, zippora
     *
     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
            '0' => 'success switch service mode',              
            '1' => 'need login',              
            '2' => 'empty or wrong mode to switch',              
            '3' => 'fail to switch service mode',              
     * @apiSuccess {Object}     data
     * @apiSuccess {String}       data.memberId
     * @apiSuccess {String}       data.serviceMode
     *
     * @apiSampleRequest
     */
    public function switchServiceMode(){

        $serviceMode = I('request.serviceMode');
        if (empty($serviceMode)) { $this->ret(2);}
        if (!in_array($serviceMode, ['ziplocker', 'zippora'])) { $this->ret(2);}

        D('Member')->updateMemberById($this->_memberId, ['service_mode' => $serviceMode]);
        $this->ret(0, [
            'memberId' => $this->_memberId,
            'serviceMode' => $serviceMode,
        ]);
    }
}
