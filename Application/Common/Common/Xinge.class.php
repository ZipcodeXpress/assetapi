<?php
namespace Common\Common;

class Xinge {

    private $pushIOS;
    private $pushAndroid;

    public function __construct() {
        $this->pushIOS = new XingeApp(C('XG_ACCESS_ID_IOS'), C('XG_SECRET_KEY_IOS'));
        $this->pushAndroid = new XingeApp(C('XG_ACCESS_ID_Android'), C('XG_SECRET_KEY_Android'));
        //$this->IOSENV = APP_STATUS == 'product' ? XingeApp::IOSENV_PROD : XingeApp::IOSENV_DEV;
        $this->IOSENV = XingeApp::IOSENV_PROD;// release app use IOSENV_PROD cert, no matter what UNIBOX_ENV
    }

    //下发Andriod单个账号
    public function PushSingleAccountAndroid($account, $title, $content)
    {
        $mess = new Message();
        $mess->setExpireTime(86400);
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setType(Message::TYPE_NOTIFICATION);
        $ret = $this->pushAndroid->PushSingleAccount(0, $account, $mess);
        return ($ret);
    }

    //下发IOS账号消息
    public function PushSingleAccountIOS($account, $title, $content)
    {
        $mess = new MessageIOS();
        $mess->setExpireTime(86400);
        $mess->setAlert($content);
        //$mess->setAlert(array('key1'=>'value1'));
        $mess->setBadge(1);
        $mess->setSound("beep.wav");
        $custom = array('key1'=>'value1', 'key2'=>'value2');
        $mess->setCustom($custom);
        $acceptTime1 = new TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime1);
        $ret = $this->pushIOS->PushSingleAccount(0, $account, $mess, $this->IOSENV);
        return $ret;
    }

    //查询某个account绑定的token
    function QueryTokensOfAccount($account)
    {
        $ret = $this->pushIOS->QueryTokensOfAccount($account);
        return ($ret);
    }
}
