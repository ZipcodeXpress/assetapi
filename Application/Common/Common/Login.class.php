<?php
namespace Common\Common;
use Think\Log\Driver;
class Login{
    const ACCESS_TOKEN_KEY = '!@#$%unibox^&*()';
    const ACCESS_TOKEN_EXPIRE = 86400;

    private function buildAccessToken(){
        return md5(\Org\Util\String::randString(20));
    }

    private function buildSecretKey($token){
        return md5($token . self::ACCESS_TOKEN_KEY);
    }

    private function getLoginKey($memberId){
        return "login_user_key_{$memberId}";
    }

    /* 设置用户token
     * @params    memberId    用户账号
     * @return    token
    */
    public function setAccessToken($memberId){
        $key = $this->getLoginKey($memberId);
        $token = $this->buildAccessToken();
        $secretKey = $this->buildSecretKey($token);

        $ret = S(C('memcache_config'))->set($key, $secretKey, self::ACCESS_TOKEN_EXPIRE);
        return $token;
    }

    /* 获取当前登录用户
     * @return    失败返回false  成功返回用户id
    */
    public function getLoginUser(){
        $accessToken = I('request._accessToken');
        $memberId = I('request._memberId');
        if (empty($accessToken) || empty($memberId)) {
            return FALSE;
        }

        $key = $this->getLoginKey($memberId);
        $sKey = $this->buildSecretKey($accessToken);

        $secretKey = S(C('memcache_config'))->get($key);
        if (empty($secretKey) || $secretKey != $sKey) {
            return FALSE;
        }

        $this->setActivityLog($memberId);  //记录活跃用户日志

        return $memberId;
    }

    public function deleteAccessToken($memberId){
        $key = $this->getLoginKey($memberId);
        return S(C('memcache_config'))->rm($key);
    }

    public function setActivityLog($memberId){
        $logger = new \Think\Log\Driver\Logger('activity');
        $log = array(
            'memberId' => $memberId,
        );
        $logger->write($log);
    }
}
