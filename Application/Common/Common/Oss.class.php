<?php
namespace Common\Common;
use Org\Util;
use Think\Log\Driver;
require_once(C('APPLICATION_PATH') . 'Common/Api/OSS/autoload.php');
use OSS\OssClient;
class Oss{
	
    const _accessKeyId = '5LD75nsAFi0vCNIO';
    const _accessKeySecret = 'bnULyiddepFj8tDCoumODz8bJXTsT3';
    /**
     * ÉÏ´«Ö¸¶¨µÄ±¾µØÎÄ¼þÄÚÈÝ
     *
     * @param OssClient $ossClient OSSClientÊµÀý
     * @param string $bucket ´æ´¢¿Õ¼äÃû³Æ
     * @param string $object ´æ´¢ÎÄ¼þÃû³Æ
     * @param string $filePath ±¾µØÎÄ¼þÂ·¾¶
     * @return null
     */
    //   public function save(){
    //$Oss = new \Common\Common\Oss();
    //$Oss -> uploadFile("images/File/ad.jpg",C('PUBLIC_PATH')."images/ad.jpg");
    //}
    public function uploadFile($object,$filePath,$flag=false){
        // if(APP_STATUS == 'product') {
            // $ossClient = new OssClient(Oss::_accessKeyId, Oss::_accessKeySecret, 'oss-us-east-1-internal.aliyuncs.com');
        // } else {
            // $ossClient = new OssClient(Oss::_accessKeyId, Oss::_accessKeySecret, 'oss-cn-beijing-internal.aliyuncs.com');
        // }
		if(APP_STATUS == 'product') {
			$ossClient = new OssClient(C('accessKeyId'), C('accessKeySecret'), 'oss-us-east-1-internal.aliyuncs.com');
        } else {
			$ossClient = new OssClient(C('accessKeyId'), C('accessKeySecret'), 'oss-cn-beijing-internal.aliyuncs.com');
        }
        $ossClient->setTimeout(3600);
        $ossClient->setConnectTimeout(10);
        $bucket = C('oss_bucket');
        try{
            $ossClient->uploadFile($bucket, $object, $filePath);
        } catch(OssException $e) {
            return false;
        }
        if($flag){
            if (!unlink($filePath))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
}
