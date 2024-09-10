<?php
namespace Common\Common;
use Think\Log\Driver;
class AdminLogin{
    const ACCESS_TOKEN_KEY = '!@#$%unibox^&*()';
    const ACCESS_TOKEN_EXPIRE = 86400;

    private function buildAccessToken(){
        return md5(\Org\Util\String::randString(20));
    }

    private function buildSecretKey($token){
        return md5($token . self::ACCESS_TOKEN_KEY);
    }

    private function getLoginKey($adminId){
        return "adminlogin_user_key_{$adminId}";
    }

    /* 设置用户token
     * @params    memberId    用户账号
     * @return    token
    */
    public function setAccessToken($adminId){
        $key = $this->getLoginKey($adminId);
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
        $adminId = I('request._adminId');
        if (empty($accessToken) || empty($adminId)) {
            return FALSE;
        }

        $key = $this->getLoginKey($adminId);
        $sKey = $this->buildSecretKey($accessToken);

        $secretKey = S(C('memcache_config'))->get($key);
        if (empty($secretKey) || $secretKey != $sKey) {
            return FALSE;
        }

        $this->setActivityLog($adminId);  //记录活跃用户日志

        return $adminId;
    }

    public function deleteAccessToken($adminId){
        $key = $this->getLoginKey($adminId);
        return S(C('memcache_config'))->rm($key);
    }

    public function setActivityLog($adminId){
        $logger = new \Think\Log\Driver\Logger('activity');
        $log = array(
            'adminId' => $adminId,
        );
        $logger->write($log);
    }
}
