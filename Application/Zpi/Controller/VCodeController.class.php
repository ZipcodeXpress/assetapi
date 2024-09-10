<?php
namespace Zpi\Controller;
use Common\Common;
use Think\Controller;

class VCodeController extends BaseController {

    /**
     * @api {post} /VCode/getVCode getVCode
     * @apiName getVCode
     * @apiGroup 01-VCode
     *
     * @apiParam {String} [email] Email & Phone 不能同时为空
     * @apiParam {String} [phone] Email & Phone 不能同时为空
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',                   
            '1' => 'wrong param',                   
            '2' => 'fail to send vcode',                   
     * @apiSuccess {Object} data
     * @apiSuccess {String} data.vid
     *
     * @apiSampleRequest
     */
    public function getVCode(){
        $email = I('request.email');
        $phone = I('request.phone');
        if (empty($email) && empty($phone)) { $this->ret(1);}

        if($email) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $this->ret(1);
            }

            $VCode = new \Common\Common\VCode();
            $vid = $VCode->send($email, 1, true);
            if ($vid === FALSE) {
                $this->ret(2);
            }
        } else {

            $VCode = new \Common\Common\VCode();
            $vid = $VCode->send($phone);
            if ($vid === FALSE) {
                $this->ret(2);
            }
        }

        $this->ret(0, [
            'vid' => $vid,
        ]);
    }


    /**
     * @api {post} /VCode/checkVCode checkVCode
     * @apiName checkVCode
     * @apiGroup 01-VCode
     *
     * @apiParam {String} [email] Email & Phone 不能同时为空
     * @apiParam {String} [phone] Email & Phone 不能同时为空
     * @apiParam {String} vid
     * @apiParam {String} vcode
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.accessToken
     * @apiSuccess {String}   data.memberId
     * @apiSuccess {Object} data.statusDetail for ziplocker, zippora (not courier)
     * @apiSuccess {String} data.statusDetail.isEmailVerified     Email是否验证
     * @apiSuccess {String} data.statusDetail.isProfileCompleted  是否完善资料（资料全部都填）
     * @apiSuccess {String} data.statusDetail.hasCreditCard       是否有绑定信用卡
     * @apiSuccess {String} data.statusDetail.hasBindAddress      是否有绑定快递地址
     * @apiSuccess {String} data.statusDetail.hasBindCabinet      是否有绑定快递柜
     * @apiSuccess {String}   data.status for zipcodexpress courier app only  (未启用)                 
            0 => 'register completed',                      
            1 => 'profile has not been completed',                  
            2 => 'member has no binded cabinet',                  
            3 => 'member has no binded credit card',                  
     * @apiSuccess {String}   data.c_status for zipcodexpress courier app only                  
            0 => 'register && official verified completed',                  
            1 => 'member has not added cetification materials',                         
            2 => 'member has not been verified by the Zippora Official',
     * @apiSuccess {String}   data.serviceMode  eg. null(空), ziplocker, zippora
     * @apiSuccess {String}   data.psd md5 of password, used to request /vcode/login
     * @apiSuccess {String} msg
            '0' => 'check vcode success',                   
            '1' => 'wrong param',                   
            '2' => 'wrong vcode',                   
     *
     * @apiSampleRequest
     */
    public function checkVCode(){

        $email = I('request.email');
        $phone = I('request.phone');
        $vid = I('request.vid');
        $vcode = I('request.vcode');

        if (empty($email) && empty($phone)) { $this->ret(1);}
        if (empty($vid && $vcode)) { $this->ret(1);}

        $VCode = new \Common\Common\VCode();
        if($email) {
            $ret = $VCode->check($email, $vid, $vcode);
        } else {
            $ret = $VCode->check($phone, $vid, $vcode);
        }

        if ($ret === FALSE) {
            $this->ret(2);
        }

        if($email) {
            $member = D('Member')->loginEmail($email, false, true);
        } else {
            $member = D('Member')->loginPhone($phone, '', true);
        }
        if (!$member) $this->ret(3);

        $Login = new \Common\Common\Login();
        $this->ret(0, [
            'accessToken' => $Login->setAccessToken($member['member_id']),
            'memberId'    => $member['member_id'],
            'serviceMode' => $member['service_mode'],
            'status' => intval($member['status']),//即将废除
            'statusDetail'         => [
                'isEmailVerified'      => $member['is_email_verified'],
                'isProfileCompleted'   => D('MemberProfile')->isProfileCompleted($member['member_id']),
                'hasCreditCard'        => $member['has_credit_card'],
                'hasBindAddress'       => D('MemberAddress')->getAddress($member['member_id']) ? 1: 0,
                'hasBindCabinet'       => D('MemberCabinet')->getMemberCabinet($member['member_id']) ? 1: 0,
            ],
            'cstatus' => intval($member['c_status']),
            'psd' => $member['password'],
        ]);
    }

    /**
     * @api {post} /VCode/login login
     * @apiName login
     * @apiGroup 01-VCode
     *
     * @apiParam {String} memberId
     * @apiParam {String} psd
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',                   
            '1' => 'wrong param',                   
            '2' => 'no member found',                   
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.accessToken
     * @apiSuccess {String}   data.memberId
     * @apiSuccess {String}   data.status for 1.ziplocker && 2.zippora && 3.courier app                 
            0 => 'register completed',                      
            1 => 'profile has not been completed',                  
            2 => 'member has no binded cabinet',                  
            3 => 'member has no binded credit card',                  
     * @apiSuccess {String}   data.c_status for courier app only                
            0 => 'register && official verified completed',                  
            1 => 'member has not added cetification materials',                         
            2 => 'member has not been verified by the Zippora Official',
     * @apiSuccess {String}   data.serviceMode  eg. null(空), ziplocker, zippora
     * @apiSuccess {String}   data.psd md5 of password, used to request /vcode/login
     * @apiSuccess {String} msg
            '0' => 'check vcode success',                   
            '1' => 'wrong param',                   
            '2' => 'wrong vcode',                   
     *
     * @apiSampleRequest
     */
    public function login(){
        $memberId = I('request.memberId');
        $psd = I('request.psd');
        if (empty($memberId)) { $this->ret(1);}
        if (empty($psd)) { $this->ret(1);}

        $member = D('Member')->checkPsd($memberId, $psd);
        if (!$member) $this->ret(2);

        $Login = new \Common\Common\Login();
        $this->ret(0, [
            'accessToken' => $Login->setAccessToken($member['member_id']),
            'memberId'    => $member['member_id'],
            'serviceMode' => $member['service_mode'],
            'status' => intval($member['status']),
            'cstatus' => intval($member['c_status']),
            'psd' => $member['password'],
        ]);
    }
}
