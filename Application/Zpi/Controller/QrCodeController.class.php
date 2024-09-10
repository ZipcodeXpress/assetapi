<?php
namespace Zpi\Controller;
use Think\Controller;
use Common\Common;

class QrCodeController extends BaseController {

    public function show(){

        $f = I('get.f');

        $QRCode = new \Common\Common\QRCode();
        $key = $QRCode->getQrKey($f);

        $img = S(C('memcache_config'))->get($key);
        if (empty($img)) { E('无效的二维码图片');}

        header("Content-type:image/png");
        echo $img;
    }


    /**
     * @api {get} /QrCode/scan scan
     * @apiName scan
     * @apiGroup 07-QrCode
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} text
     *
     * @apiSuccess {Number} ret
            '0' => 'scan success',                  
            '1' => 'need login',                
            '2' => 'empty text',                
            '3' => 'not support qrcode',            
            '4' => 'qrcode has expired',            
            '5' => 'not support bussiness type',            
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSampleRequest
     */
    public function scan(){

        if(empty($this->_memberId)) { $this->ret(1);}

        $text = I('request.text');

        if(empty($text)) { $this->ret(2);} else {
            $text = explode('#', $text)[1];
        }

        $QRCode = new \Common\Common\QRCode();
        $retArr = $QRCode->scan($text, $this->_memberId);
        if($retArr['ret']) {
            $this->ret($retArr['ret'] + 2);
        } else {
            $this->ret(0, $retArr);
        }
    }
}
