<?php
namespace Cabinet\Controller;
use Think\Controller;
use Common\Common;

class ProductController extends BaseController {

    /**
     * @api {post} /zippora/initData initData
     * @apiName initData
     * @apiUse initData
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getAccessToken getAccessToken
     * @apiName getAccessToken
     * @apiUse getAccessToken
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/checkAccessToken checkAccessToken
     * @apiName checkAccessToken
     * @apiUse checkAccessToken
     * @apiGroup 01-common
     */



    /**
     * @api {post} /zippora/testa 01-testa
     * @apiDescription test1
     * @apiName testa
     * @apiUse testa
     * @apiGroup 14-admin
     */
    public function __construct() {
        
        parent::__construct('product');
    }
    /**
     * @apiDefine testa
     * @apiParam {String} tt
     * @apiSuccess {Number} ret                                     
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @sendSampleRequest
     */
    public function testa() {
        $abc['data'] =null;
		$this->ret(0, $abc);
    }
    /**
     * @apiDefine getAccessToken
     * @apiParam {String} apiKey
     * @apiParam {String} kts 
     * @apiParam {String} cabinetId
     * @apiParam {String} sign 
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                      
            '1' => 'empty apiKey',                                      
            '2' => 'empty kts',                                      
            '3' => 'empty cabinetId',                                      
            '4' => 'empty sign',                                      
            '5' => 'invalid cabinet',                                      
            '6' => 'invalid api secret',                                      
            '7' => 'verify sign fail',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.accessToken
     * @apiSuccess {Number}   data.expire
     * @apiSuccess {String}   data.cabinetId
     * @apiSuccess {String}   data.address
     * @apiSuccess {String}   data.zipcode
     * @apiSuccess {String}   data.serviceType  'ziplocker','zippora','store'
     * @apiSuccessExample {json} Success-Response:
     *
        {
          "ret": 0,
          "msg": "Get Access Token Success",
          "data": {
            "accessToken": "cfcd1e5477e465230ace070d70a78e1f",
            "expire": 86400,
            "cabinetId": 123,
            "address": "California XXXX",
          }
        }
     *
     * @sendSampleRequest
     */
    public function getAccessToken() {
        parent::getAccessToken();
    }

    /**
     * @apiDefine checkAccessToken
     * @apiParam {String} accessToken
     *
     * @apiSuccess {Number} ret
            '0' => 'valid accessToken',                                      
            '1' => 'invalid accessToken',                                      
     *
     * @sendSampleRequest
     */
    public function checkAccessToken() {
        parent::checkAccessToken();
    }

 
}
