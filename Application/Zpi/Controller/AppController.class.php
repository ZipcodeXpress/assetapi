<?php
namespace Zpi\Controller;
use Think\Controller;
use Common\Common;

class AppController extends BaseController {

    /**
     * @api {get} /app/version version
     * @apiName version
     * @apiGroup 09-app
     *
     * @apiSuccess {Number} ret
            '0' => 'success'
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.ios
     * @apiSuccess {String}     data.ios.version
     * @apiSuccess {String}     data.ios.desc
     * @apiSuccess {Object}   data.android
     * @apiSuccess {String}     data.android.version
     * @apiSuccess {String}     data.android.desc
     *
     * @apiSampleRequest
     */
    public function version(){

        $AppVersion = D('AppVersion');
        $ios = $AppVersion->getLatestVersionByPlatform('ios', 'zipcodexpress');
        $ado = $AppVersion->getLatestVersionByPlatform('android', 'zipcodexpress');
        $versionList = [];
        if($ios) { $versionList['ios'] = [
            'version' => $ios['version'],
            'desc' => $ios['desc'],
            'createTime' => date('Y-m-d H:i:s', $ios['create_time']),
        ];}
        if($ado) { $versionList['android'] = [
            'version' => $ado['version'],
            'desc' => $ado['desc'],
            'createTime' => date('Y-m-d H:i:s', $ado['create_time']),
        ];}

        $this->ret(0, $versionList);
    }
}
