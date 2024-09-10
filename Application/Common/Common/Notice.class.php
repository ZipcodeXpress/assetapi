<?php
namespace Common\Common;
use Think\Model;

/***
    $Think.config.NOTICE.NT_CREDITCARD_CHARGE_FAIL    = 10;//信用卡扣费失败
    $Think.config.NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK   = 21;//有包裹需要取件
*/

class Notice {

    static $_log = null;
    public function __construct() {

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('notice');
        }
    }

    public function notice($NT=1, $memberId, $params=array()) {
        if($NT==0) return false;
        if(empty($memberId)) return false;

        $ntSTRConf = array_flip(C('NOTICE'));
        $NTSTR = $ntSTRConf[$NT];

        $member = D('MemberView')->getMemberView($memberId);
        //增加OC用户查找
        if(count($member)<=0) {
        $mempm = D('ZtStore')->getStore([
                'oc_order_id' => $memberId
                    ]);

        $data = [
            'email' => $mempm['oc_to_email'],
            'phone' => $mempm['oc_to_phone'],
            'nickname' => 'guest',
            'cabinetId' => $params['cabinet_id'],
            'pickCode' => $params['pick_code'],
        ];
        } else {
        
        $data = [
            'email' => $member['email'],
            'phone' => $member['phone'],
            'nickname' => ucwords($member['nick_name'] ? : $member['first_name'].' '.$member['last_name']),
            'cabinetId' => $params['cabinet_id'],
            'pickCode' => $params['pick_code'],
        ];
        }

        if($params['cabinet_id']) {
            $data['address'] = D('Cabinet')->getCabinetAddress($params['cabinet_id']);
            $data['address_url'] = D('Cabinet')->getCabinetAddressUrl($params['cabinet_id']) ? : '';
        }

        if($NTSTR == 'NT_CREDITCARD_CHARGE_FAIL' && $params['card_last4']) {
            $data['cardCode'] = '**** **** **** '.$params['card_last4'];
        }

        $ntEmailTmplConf = C('NOTICE_TMPL');
        $emailTmpl = $ntEmailTmplConf[$NTSTR];

        $subject = $emailTmpl['subject'];
        $body = $emailTmpl['body'];
        $sms  = $emailTmpl['sms'];

        /**
        $phone = $member['phone'];
        $email = $member['email'];
        **/
        $phone = $data['phone'];
        $email = $data['email'];
        //$email = 'tangzhen@unibox.com.cn';
 
        $countrycode=C('COUTRYCODE');
        foreach ($data as $key => $value) {
            $search = sprintf('{%s}', $key);
            if($key == 'address' && !empty($data['address_url'])) {
                $value = '<a href="'.$data['address_url'].'">'.$value.'</a>';
            }
            $body = str_replace($search, $value, $body);
            if($sms) { $sms = str_replace($search, $value, $sms);}
        }

        D('Message')->insertMessage($memberId, $subject, $body, NULL, NULL);

        //send email
        if($email) {
            $maildata = [
                'mailfrom' => C('SMTP_USER_EMAIL'),
                'mailto' => $email,
                'subject' => $subject,
                'body' => $body,
            ];

            $loginfo = [
                'notice' => $NTSTR,
                'memberId' => $memberId,
                'data' => $data,
                'mail' => $maildata,
            ];

            $Email = new Email();
            if($Email->sendByPHPMailer($maildata)) {
                $loginfo['status'] = 'send mail success';
            } else {
                $loginfo['status'] = 'send mail fail';
            }
            self::$_log->write($loginfo);
        }

        //send sms
        
        if($sms && $phone) {
            $loginfo = [
                'notice' => $NTSTR,
                'memberId' => $memberId,
                'data' => $data,
                'sms' => $sms,
                'phone' => $phone,
            ];
            
            $SMS = new SMS();
            if($SMS->send($phone, $sms, $memberId,$countrycode)) {
                $loginfo['status'] = 'send sms success';
            } else {
                $loginfo['status'] = 'send sms fail';
            }
  
           self::$_log->write($loginfo);
        }
       
        //send xinge push message
        if($memberId) {
            $loginfo = [
                'notice' => $NTSTR,
                'memberId' => $memberId,
                'data' => $data,
                'xingeMsg' => $sms,
            ];

            $Xinge = new \Common\Common\Xinge();
            $retIOS = $Xinge->PushSingleAccountIOS(''.$memberId, $subject, $sms);
            $retAndroid = $Xinge->PushSingleAccountAndroid(''.$memberId, $subject, $sms);

            $loginfo['retIOS'] = $retIOS;
            $loginfo['retAndroid'] = $retAndroid;
            self::$_log->write($loginfo);
        }

        return true;
    }
}
