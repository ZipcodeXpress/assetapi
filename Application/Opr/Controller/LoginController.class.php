<?php
namespace Opr\Controller;
use Common\Common;
use Think\Controller;

class LoginController extends BaseController {

    /**
     * @api {post} /login/checkEmail 01-checkEmail
     * @apiName checkEmail
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} email
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'email available',                   
            '1' => 'wrong param',                   
            '2' => 'wrong email format',                   
            '3' => 'email has been registered',                   
     *
     * @sendSampleRequest
     */
    public function checkEmail(){

        $email = I('request.email', '', 'trim');
        $Member = D('member');

        if (!($email)) $this->ret(1);
        if (!$Member->isEmail($email)) $this->ret(2);
        if ($Member->getMemberByEmail($email)) $this->ret(3);

        $this->ret(0);
    }

    /**
     * @api {post} /login/register 02-register
     * @apiName register
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} email
     * @apiParam {String} psd1
     * @apiParam {String} psd2
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'register success',              
            '1' => 'wrong param',              
            '2' => 'wrong email format',              
            '3' => 'email has been registered',                   
            '4' => 'wrong repeate password',              
            '5' => 'send email fail',              
            '6' => 'register fail',              
     * @apiSuccess {Object} data 同 Zpi/VCode/checkVCode
     *
     * @sendSampleRequest
     */
    public function register(){

        $email  = I('request.email', '', 'trim');
        $psd1   = I('request.psd1');
        $psd2   = I('request.psd2');
        $Member = D('Member');

        if (!($email && $psd1 && $psd2)) $this->ret(1);
        if (!$Member->isEmail($email)) $this->ret(2);
        if ($Member->getMemberByEmail($email)) $this->ret(3);
        if ($psd1 != $psd2) $this->ret(4);

        //register
        $memberId = $Member->register($email, '', $psd1);
        $member = $Member->getMemberById($memberId);

        $vcode = mt_rand(1000, 9999);
        S(C('memcache_config'))->set('register_verify_'.$memberId, $vcode);

        $Email = new \Common\Common\Email();
        if(!$Email->sendEmail($email, $member['member_id'], 'register', ['vcode'=>$vcode])) {
            $this->ret(5);
        }

        $this->ret(0, $this->loginMember($member));
    }
    
    
        /**
     * @api {post} /login/registerDomain 06-registerDomain
     * @apiName registerDomain
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} email
     * @apiParam {String} [phone]
     * @apiParam {String} psd1
     * @apiParam {String} psd2
     * @apiParam {String} domain
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'register success',              
            '1' => 'wrong param',              
            '2' => 'wrong email format',              
            '4' => 'wrong repeate password',              
            '6' => 'register fail',              
            '8' => 'this account has been verified',                
            '10' => 'email has been registered',              
     * @apiSuccess {Object} data 同 Zpi/VCode/checkVCode
     *
     * @sendSampleRequest
     */
    public function registerDomain(){

        $email  = I('request.email', '', 'trim');
        $phone  = I('request.phone', '', 'trim');
        $psd1   = I('request.psd1');
        $psd2   = I('request.psd2');
        $Member = D('Member');
        $domain  = I('request.domain', '', 'trim');

        if (!($email && $psd1 && $psd2)) $this->ret(1);
        if (!$Member->isEmail($email)) $this->ret(2);
        if ($psd1 != $psd2) $this->ret(4);
        
        //member exists (yes)
            //member is exported (yes)
                // member has been verified (yes)
                    //member has been verified (8)
                // member has been verified (no)
                    //check member plan
                    //verify member
            //member is exported (no)
                //member has been registered (10)
        //member exists (no)
          //register new member

        $member = $Member->getMemberByEmail($email);
        if ($member) {

            if($member['register_type'] == 'register') {

                //'10' => 'email has been registered',
                $this->ret(10);

            } else {

                //'8' => 'this account has been verified',
                if ($member['is_email_verified'] == 1) $this->ret(8);

                //set psd
                $salt = mt_rand(1,10000);
                $psd = md5($psd1.$salt);
                $Member->updateMemberById($member['member_id'], [
                    'password'=>$psd,
                    'salt'=>$salt,
                    'is_email_verified'=>1,
                ]);
            }
        } else {
            $memberId = $Member->registerDomain($email, $phone, $psd1);
            $member = $Member->getMemberById($memberId);
        }

        
         //自动绑定用户区域
        $now = time();
        if(!$domain) $domain = '10014';
        $bind = [
            'member_id' => $memberId,
            'organization_id' => $domain,
            'unit_id' => 0,
            //'apply_photo_group_id' => $photoGroupId,
            'apply_time' => $now,
            //'approve_time'
            'approve_time' => $now,
            'approve_status' => 0,
            'last_charge_time' => $now,
            'charge_day' => 0,//没有前导0的day, 1~31
            'price' => 0,
            'cost' => 0,
            'status' => 0,//wait for payment
            'cancel_time' => 0,
            'create_time' => $now,
        ];

        $ret = D('OMemberOrganization')->insertMemberApartment($bind);
        
				$this->ret(0, $this->loginMember($member));
    }

    /**
     * @api {post} /login/login 03-login
     * @apiName login
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} [email]       email, phone两者必须有且只有一个不为空
     * @apiParam {String} [phone]       email, phone两者必须有且只有一个不为空
     * @apiParam {String} [userid]      增加用户ID登录方式
     * @apiParam {String} psd           md5(password)
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'login success',                         
            '1' => 'wrong param',                           
            '2' => 'wrong email format',                           
            '3' => 'member not exist',                           
            '5' => 'wrong email or password',                           
     * @apiSuccess {Object} data 同 Zpi/VCode/checkVCode
     *
     * @sendSampleRequest
     */
    public function login(){

        $email  = I('request.email', '', 'trim');
        $phone  = I('request.phone', '', 'trim');
        $userid  = I('request.userid', '', 'trim');
        $psd    = I('request.psd');
        $Member = D('member');

        if($email) {
            if (!$Member->isEmail($email)) $this->ret(2);
            if (!$Member->getMemberByEmail($email)) $this->ret(3);
            $member = $Member->loginEmail($email, $psd);
        } else if($phone) {
            if (!$Member->getMemberByPhone($phone)) $this->ret(3);
            $member = $Member->loginPhone($phone, $psd);
        } else if($userid) {
            if (!$Member->getMemberById($userid)) $this->ret(3);
            $member = $Member->loginId($userid, $psd);
        }  else {
            $this->ret(1);
        }
        if (!$member) $this->ret(5);
        $this->ret(0, $this->loginMember($member));
    }

    /**
     * @api {post} /login/forgetPsd 04-forgetPsd
     * @apiName forgetPsd
     * @apiDescription 忘记密码接口将向用户Email发送带有重置密码验证码(vcode)的邮件, 同时返回相关memberId
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} email
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'resend email success',                   
            '1' => 'wrong param',                   
            '2' => 'wrong email format',                   
            '3' => 'member not exist',                   
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.memberId  用于后面请求resetPsd
     *
     * @sendSampleRequest
     */
    public function forgetpsd(){

        $email  = I('request.email', '', 'trim');
        $Member = D('Member');

        if (!($email)) $this->ret(1);
        if (!$Member->isEmail($email)) $this->ret(2);

        $member = $Member->getMemberByEmail($email);
        if (!$member) $this->ret(3);

        $vcode = mt_rand(1000, 9999);
        S(C('memcache_config'))->set('resetpsd_verify_'.$member['member_id'], $vcode);
        $Email = new \Common\Common\Email();
        $Email->sendEmail($email, $member['member_id'], 'resetpsd', ['vcode'=>$vcode]);
        S(C('memcache_config'))->rm('resetpsd_verify_'.$memberId);

        $this->ret(0, [
            'memberId'    => $member['member_id'],
        ]);
    }

    /**
     * @api {post} /login/resetPsd 05-resetPsd
     * @apiName resetPsd
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} memberId  来自上一个请求forgetPsd返回的data.memberId
     * @apiParam {String} vcode 用户邮箱收到的验证码
     * @apiParam {String} psd1
     * @apiParam {String} psd2
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'resetpsd success',                   
            '1' => 'wrong param',                   
            '2' => 'member not exist',                   
            '3' => 'wrong repeate password',                   
            '4' => 'wrong verify code',                   
     *
     *
     * @sendSampleRequest
     */
    public function resetPsd(){

        $memberId  = I('request.memberId');
        $vcode     = I('request.vcode');
        $psd1      = I('request.psd1');
        $psd2      = I('request.psd2');

        $Member = D('Member');

        if (!($psd1 && $psd2 && $vcode && $memberId)) $this->ret(1);
        $member = $Member->getMemberById($memberId);
        if (!$member) $this->ret(2);
        if ($psd1 != $psd2) $this->ret(3);

        if (!(S(C('memcache_config'))->get('resetpsd_verify_'.$memberId) == $vcode)) $this->ret(4);

        #udpate new psd
        $psd = md5($psd1.$member['salt']);
        $Member->updateMemberById($memberId, array('password'=>$psd));

        #delete resetPsd token
        S(C('memcache_config'))->rm('resetpsd_verify_'.$memberId);

        #delete current user any login token
        $Login = new \Common\Common\Login();
        $Login->deleteAccessToken($memberId);

        $this->ret(0);
    }

    /**
     * @api {post} /login/reSendEmail 01-reSendEmail
     * @apiDescription 重发验证邮件（用户已登录未验证状态下，可以请求重发验证邮件用于验证自己的Email)
     * @apiName reSendEmail
     * @apiGroup 01-Email Logined
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'resend email success',                   
            '1' => 'need login',                        
            '2' => 'member has no email',                        
     *
     * @sendSampleRequest
     */
    public function reSendEmail() {

        if (empty($this->_memberId)) $this->ret(1);

        $Member = D('Member');
        $member = $Member->getMemberById($this->_memberId);

        if($member['email']) {
            $vcode = mt_rand(1000, 9999);
            S(C('memcache_config'))->set('register_verify_'.$member['member_id'], $vcode,86400);
            $Email = new \Common\Common\Email();
            $Email->sendEmail($member['email'], $member['member_id'], 'register', ['vcode'=>$vcode]);
            $this->ret(0);
        } else {
            $this->ret(2);
        }
    }

    /**
     * @api {post} /login/logout 02-logout
     * @apiName logout
     * @apiGroup 01-Email Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'logout success',                   
            '1' => 'need login',                   
     *
     * @sendSampleRequest
     */
    public function logout(){

        if (empty($this->_memberId)) $this->ret(1);

        $Login = new \Common\Common\Login();
        if (empty($Login->deleteAccessToken($this->_memberId))) $this->ret(2);

        $this->ret(0);
    }

    /**
     * @api {post} /login/changePsd 03-changePsd
     * @apiName changePsd
     * @apiGroup 01-Email Logined
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} oldPsd
     * @apiParam {String} psd1
     * @apiParam {String} psd2
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'changepsd success',                 
            '1' => 'need login',                
            '2' => 'wrong param',                
            '3' => 'wrong repeate password',                
            '4' => 'member not exist',                
            '5' => 'wrong old password',                
     *
     * @sendSampleRequest
     */
    public function changePsd(){

        if (empty($this->_memberId)) $this->ret(1);

        $oldPsd = I('request.oldPsd');
        $psd1   = I('request.psd1');
        $psd2   = I('request.psd2');
        $Member = D('Member');

        if (empty($oldPsd && $psd1 && $psd2)) $this->ret(2);
        if ($psd1 != $psd2) $this->ret(3);

        $member = $Member->getMemberById($this->_memberId);
        if (empty($member)) $this->ret(4);

        $oldPsdVal = md5($oldPsd.$member['salt']);
        if ($oldPsdVal != $member['password']) $this->ret(5);

        $psd = md5($psd1.$member['salt']);
        $Member->updateMemberById($this->_memberId, array('password'=>$psd));

        $this->ret(0);
    }

    /**
     * @api {post} /login/verifyEmail 04-verifyEmail
     * @apiName verifyEmail
     * @apiGroup 01-Email Logined
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} vcode
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'verify email success',              
            '1' => 'need login',              
            '2' => 'empty vcode',              
            '3' => 'fail to verify email',              
     *
     * @sendSampleRequest
     */
    public function verifyEmail(){

        if(empty($this->_memberId)) { $this->ret(1);}

        $vcode  = I('request.vcode');
        $Member = D('member');

        if (empty($vcode)) $this->ret(2);

        if (!(S(C('memcache_config'))->get('register_verify_'.$this->_memberId) == $vcode)) $this->ret(3);

        $Member->updateMemberById($this->_memberId, ['is_email_verified' => 1]); 
        S(C('memcache_config'))->rm('register_verify_'.$this->_memberId);
        $this->ret(0);
    }

    private function loginMember($member) {

        $Login = new \Common\Common\Login();
        return [
            'accessToken' => $Login->setAccessToken($member['member_id']),
            'memberId'    => $member['member_id'],
            'serviceMode' => $member['service_mode'],
            'status' => intval($member['status']),//即将废除
            'statusDetail'         => [
                'isEmailVerified'      => $member['is_email_verified'],
                //'isProfileCompleted'   => D('MemberProfile')->isProfileCompleted($member['member_id']),
				'isProfileCompleted'   => intval($member['is_profile_completed']),
                'hasCreditCard'        => $member['has_credit_card'],
                //'hasBindAddress'       => D('MemberAddress')->getAddress($member['member_id']) ? 1: 0,
                'hasBindAddress'       => 1,
                'hasBindCabinet'       => D('MemberCabinet')->getMemberCabinet($member['member_id']) ? 1: 0,
            ],
            'cstatus' => intval($member['c_status']),
            'psd' => $member['password'],
        ];
    }

    /**
     * @api {post} /login/verifyAccount 11-verifyAccount
     * @apiName verifyAccount
     * @apiGroup 01-Email Login
     *
     * @apiParam {String} email
     * @apiParam {String} [phone]
     * @apiParam {String} psd1
     * @apiParam {String} psd2
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'register success',              
            '1' => 'wrong param',              
            '2' => 'wrong email format',              
            '4' => 'wrong repeate password',              
            '6' => 'register fail',              
            '8' => 'this account has been verified',                
            '10' => 'email has been registered',                
     * @apiSuccess {Object} data 同 Zpi/VCode/checkVCode
     *
     * @sendSampleRequest
     */
    public function verifyAccount(){

        $email  = I('request.email', '', 'trim');
        $phone  = I('request.phone', '', 'trim');
        $psd1   = I('request.psd1');
        $psd2   = I('request.psd2');
        $Member = D('Member');

        if (!($email && $psd1 && $psd2)) $this->ret(1);
        if (!$Member->isEmail($email)) $this->ret(2);
        if ($psd1 != $psd2) $this->ret(4);

        //member exists (yes)
            //member is exported (yes)
                // member has been verified (yes)
                    //member has been verified (8)
                // member has been verified (no)
                    //check member plan
                    //verify member
            //member is exported (no)
                //member has been registered (10)
        //member exists (no)
          //register new member

        $member = $Member->getMemberByEmail($email);
        if ($member) {

            if($member['register_type'] == 'register') {

                //'10' => 'email has been registered',
                $this->ret(10);

            } else {

                //'8' => 'this account has been verified',
                if ($member['is_email_verified'] == 1) $this->ret(8);

                //if not verified, member plan must be exists for imported member
                $memberPlan = D('OMemberOrganization')->getMember($member['member_id'], null, false);
                if($memberPlan) {
                    //charge signup fee
                    if($memberPlan['charge_rule']) {
                        D('OCharge')->charge(
                            $memberPlan['member_id'],
                            $memberPlan['organization_id'],
                            $memberPlan['charge_rule'],
                            'signup_fee'
                        );
                    }
                } else {
                    //'9' => 'this account has no organization plan',
                    $this->ret(9);
                }

                //set psd
                $salt = mt_rand(1,10000);
                $psd = md5($psd1.$salt);
                $Member->updateMemberById($member['member_id'], [
                    'password'=>$psd,
                    'salt'=>$salt,
                    'is_email_verified'=>1,
                ]);
            }
        } else {
            $memberId = $Member->register($email, $phone, $psd1);
            $member = $Member->getMemberById($memberId);
        }

        $this->ret(0, $this->loginMember($member));
    }
//
    //发送邮件 （资产柜新加）   
	 /**
	 * @api {post} /login/sendMailNew 12-sendMailNew
	 * @apiDescription 发邮件
     * @apiName sendMailNew
     * @apiGroup 01-Email Login
     *
     * @apiParam {String}   _toAdd
     * @apiParam {String}   _content
     * @apiParam {String}   _subject
     * @apiParam {String}   _fromAdd
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                                     
            '1' => 'invalid accesstoken',                                                                                                                
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.rfid
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "member": {
     *             "memberId": "10001",
     *             "rfid": "123"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function sendMailNew() {
        $toAdd    = I('request._toAdd');
        $content    = I('request._content');
        $subject    = I('request._subject');
        $fromAdd    = I('request._fromAdd');
        if(empty($toAdd ))  { $this->ret(2);}
		if(empty($content ))  { $this->ret(3);}
		if(empty($subject ))  { $this->ret(4);}
        //$toAdd='179238846@qq.com';
        //$subject = '测试一下'; 
        //$content = '我来测试';
        //$headers[] = 'From: admin@zipcodexpress.com';
        $headers=$fromAdd;
        //$flag= mail($toAdd, $subject, $content, implode("\r\n", $headers));
        $flag= mail($toAdd, $subject, $content, $headers);
        if($flag)
        {
         $this->ret(0);
        }
        $this->ret(1);
    }
//
    public function send() {
        S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
            'member_id' => 21130,
            'data' => [
                'cabinet_id' => 10003,
                'pick_code' => 'TestSMS',
            ]
        ]));
    }
}
