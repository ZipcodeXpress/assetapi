<?php
namespace Common\Common;
class SMS {

	public function send($phone, $message, $memberId=null,$countrycode) {
        if(preg_match_all('/\d+/', $phone, $matches))
        {
               $phone = implode($matches[0]);
        }
        if($memberId) {
              return D('MessageSMS')->insertMessage($phone, $message, $memberId,$countrycode);
               }
        else
        {
            return false;
        }
       /* 
        $smsGateway = new SMSGateway('yonghuiz@hotmail.com', 'richardz1');

        if(preg_match_all('/\d+/', $phone, $matches)) {
            $phone = implode($matches[0]);
        }
        if(strlen($phone) !== 10) {
            //don't send sms to phone in China
            return false;
        }

        //$deviceID = 69427;
        //$deviceID = $smsGateway->getDeviceId();
        //$phone = '5127347755'; richard's phone

        $options = [
            'send_at' => strtotime('+1 minutes'), // Send the message in 10 minutes
            'expires_at' => strtotime('+1 hour') // Cancel the message in 1 hour if the message is not yet sent
        ];

        //Please note options is no required and can be left out
        //return $smsGateway->sendMessageToNumber($phone, $message, $deviceID);
        return $smsGateway->sendMessageToNumber($phone, $message);
        */
    }
}
