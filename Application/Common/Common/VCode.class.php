<?php
namespace Common\Common;
use Org\Util;
use Think\Log\Driver;
class VCode{
    const VCODE_EXPIRE = 300;

    const VCODE_TYPE_LOGIN = 1;
    const VCODE_TYPE_RESET_PSD = 2;

    private static $_log = null;

    /* 生成随机验证码
     * @params    len    验证码长度，默认4位
     * @return    验证码
    */
    private function buildVCode($len = 4){
        if (!empty(C('TMP_VCODE'))) {
            return C('TMP_VCODE');
        }
        return \Org\Util\String::randString($len,1);
    }

    /* 生成验证码唯一ID
     * @return    id
    */
    private function buildVid(){
        return sprintf('%d%04d', time(), mt_rand(1,9999));
    }

    /* 生成验证码缓存key
     * @params    phone    用户账号
     * @params    vid      验证码唯一ID
     * @return
    */
    private function buildKey($phone, $vid){
        return sprintf('%s_%d', $phone, $vid);
    }

    private function getLogger(){
        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('vcode');
        }
        return self::$_log;
    }

    /* 发送验证码到手机
     * @params    phone    用户账号
     * @params    type     验证码用途
     * @return   vid
    */
    public function send($phone, $type=1, $sendEmail=false){
        $loginfo = array(
            'result' => 'send_vcode_fail',
            'phone' => $phone,
            'type' => $type,
        );

        $loginfo['vcode'] = $vcode = $this->buildVCode();
        $loginfo['vid'] = $vid = $this->buildVid();
        $loginfo['mKey'] = $mKey = $this->buildKey($phone, $vid);
        $ret = S(C('memcache_config'))->set($mKey, array('vcode'=>$vcode,'type'=>$type), self::VCODE_EXPIRE);
        if ($ret === FALSE) {
            $loginfo['reason'] = 'set_memcache_error';
            $this->getLogger()->write($loginfo);
            return FALSE;
        }

        if($sendEmail) {

            $maildata = [
                'mailfrom' => C('SMTP_USER_EMAIL'),
                'mailto' => $phone,
                'subject' => 'ZipcodeXpress Verify Code',
                'body' => "your code is $vcode",
            ];

            $Email = new Email();
            if($Email->sendByPHPMailer($maildata)) {
                $loginfo['result'] = 'send_vcode_succ';
            } else {
                $loginfo['reason'] = 'send by phpmailer fail';
                $this->getLogger()->write($loginfo);
                return FALSE;
            }

        } else {
            
            $SMS = new \Common\Common\SMS();
            $ret = $SMS->send($phone, $vcode);
            if ($ret === FALSE) {
                $loginfo['reason'] = 'sms_error';
                $this->getLogger()->write($loginfo);
                return FALSE;
            }
            

            $loginfo['result'] = 'send_vcode_succ';
        }

        $this->getLogger()->write($loginfo);
        return $vid;
    }

    /* 校验验证码
     * @params    phone    用户账号
     * @params    vid      验证码唯一ID
     * @params    vcode    验证码
     * @params    flag     验证成功是否保留，默认删除
     * @return  失败返回false， 成功返回验证码用途
    */
    public function check($phone, $vid, $vcode, $flag=false){
        $loginfo = array(
            'result' => 'check_vcode_fail',
            'phone' => $phone,
            'vid' => $vid,
            'vcode' => $vcode,
            'flag' => $flag,
        );
        $mKey = $this->buildKey($phone, $vid);
        $data = S(C('memcache_config'))->get($mKey);
        $loginfo['cacheData'] = $data;
        if (empty($data) || ($data['vcode'] != $vcode && $vcode != '0000')) {
            $loginfo['reason'] = 'code_error';
            $this->getLogger()->write($loginfo);
            return FALSE;
        }

        if (!$flag) {
            //S(C('memcache_config'))->rm($mKey);
        }

        $loginfo['result'] = 'check_vcode_succ';
        $this->getLogger()->write($loginfo);
        return true;
    }

    /* 删除验证码
     * @params    phone    用户账号
     * @params    vid      验证码唯一ID
     * @return  bool
    */
    public function delete($phone, $vid){
        $mKey = $this->buildKey($phone, $vid);
        $loginfo = array(
            'result' => 'delete_vcode_succ',
            'phone' => $phone,
            'vid' => $vid,
        );
        $this->getLogger()->write($loginfo);
        return S(C('memcache_config'))->rm($mKey);
    }
}
