<?php
namespace Common\Common;
use Think\Log\Driver;

require_once(APP_PATH . 'Common/Api/phpqrcode/phpqrcode.php');
class QRCode{
    const QRCODE_DEFAULT_EXPIRE = 300;
    private static $_log = null;

    private function getLogger(){
        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('qrcode');
        }
        return self::$_log;
    }

    public function getQrKey($name){
        return "qrcode_pic_{$name}";
    }

    public function buildQRCode($type, $data = array()){
        $text = '';
        $retArr = array('ret'=>0,'url'=>'');
        switch ($type) {
            case 'member':
                $sceneId = sprintf('unibox_qrcodetmp_%s_%d_%d', $type, time(), mt_rand(1,1000000));
                $text = C('DOWNLOAD_APP_ADDRESS').'#'.$sceneId;
                break;
            default:
                $retArr['ret'] = 1;
                return $retArr;
        }
        $retArr['text'] = $sceneId;

        $expire = isset($data['expire']) ? $data['expire'] : self::QRCODE_DEFAULT_EXPIRE;
        S(C('memcache_config'))->set($sceneId, 1, $expire);

        $name = md5($text . mt_rand(1,1000));
        ob_start();
        \Common\Api\phpqrcode\QRcode::png($text);
        $img = ob_get_contents();
        ob_end_clean();//清空缓存

        $key = $this->getQrKey($name);
        S(C('memcache_config'))->set($key, $img, $expire);
        // unlink($path);

        $retArr['url'] = C('WWW_ADDRESS') . '/zpi/qrCode/show?f=' . $name;
        return $retArr;
    }

    public function scan($text, $memberId){
        $this->getLogger()->write([
            'text' => $text,
            'memberId' => $memberId,
        ]);
        $retArr = array('ret' => 0);
        $tmp = explode('_', $text);
        if ($tmp[0] != 'unibox') {
            $retArr['ret'] = 1;
            $retArr['error'] = 'not support qrcode';
            return $retArr;
        }

        if ($tmp[1] == 'qrcodetmp') {
            $ret = S(C('memcache_config'))->get($text);
            if (empty($ret)) {
                $retArr['ret'] = 2;
                $retArr['error'] = 'qrcode has expired';
                return $retArr;
            }
            S(C('memcache_config'))->rm($text);
        } else {
            $retArr['ret'] = 3;
            $retArr['error'] = 'not support bussiness type';
        }

        switch ($tmp[2]) {
            case 'member':
                S(C('redis_config'))->proxy('RPUSH', $text, json_encode(array('memberId'=>$memberId)));
                break;
            default:
                $retArr['ret'] = 3;
                $retArr['error'] = 'not support bussiness type';
                break;
        }

        $retArr['type'] = $tmp[1];
        return $retArr;
    }

    public function createQRCodeByUrl($url){

        \Common\Api\phpqrcode\QRcode::png($url);
    }
}
