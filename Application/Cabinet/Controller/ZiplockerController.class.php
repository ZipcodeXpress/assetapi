<?php
namespace Cabinet\Controller;
use Think\Controller;
use Common\Common;

class ZiplockerController extends BaseController {

    /**
     * @api {post} /ziplocker/getAccessToken getAccessToken
     * @apiName getAccessToken
     * @apiUse getAccessToken
     * @apiGroup 01-common
     */

    /**
     * @api {post} /ziplocker/checkAccessToken checkAccessToken
     * @apiName checkAccessToken
     * @apiUse checkAccessToken
     * @apiGroup 01-common
     */

    /**
     * @api {post} /ziplocker/getBoxConfig getBoxConfig
     * @apiDescription 初始化box数据
     * @apiName getBoxConfig
     * @apiUse getBoxConfig
     * @apiGroup 01-common
     */

    /**
     * @api {post} /ziplocker/getBoxModelList getBoxModelList
     * @apiDescription 获取箱体类型数据
     * @apiName getBoxModelList
     * @apiUse getBoxModelList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /ziplocker/preAuthForBox preAuthForBox
     * @apiDescription 请求某个型号的box
     * @apiName preAuthForBox
     * @apiUse preAuthForBox
     * @apiGroup 01-common
     */

    /**
     * @api {post} /ziplocker/blockBox blockBox
     * @apiDescription 设置某个Box状态为Blocked
     * @apiName blockBox
     * @apiUse blockBox
     * @apiGroup 01-common
     */

    /** pick && deliver
     * @api {post} /ziplocker/getAppQRUrl 01-getAppQRUrl
     * @apiDescription 获取member身份验证二维码
     * @apiName getAppQRUrl
     * @apiUse getAppQRUrl
     * @apiGroup 02-pick-deliver
     */

    /**
     * @api {post} /ziplocker/getAppScanResult 02-getAppScanResult
     * @apiDescription 获取member身份验证扫码结果
     * @apiName getAppScanResult
     * @apiUse getAppScanResult
     * @apiGroup 02-pick-deliver
     */

    /**
     * @api {post} /ziplocker/resendPickCode 03-resendPickCode
     * @apiDescription 重发取件码
     * @apiName resendPickCode
     * @apiUse resendPickCode
     * @apiGroup 02-pick-deliver
     */

    /**
     * @api {post} /ziplocker/proveCode 04-proveCode
     * @apiDescription 验证取货码OR货物条形码
     * @apiName proveCode
     * @apiUse proveCode
     * @apiGroup 02-pick-deliver
     */

    /**
     * @api {post} /ziplocker/commitForDeliver 05-commitForDeliver
     * @apiDescription 在机器上请求更新order状态(无论存取)
     * @apiName commitForDeliver
     * @apiUse commitForDeliver
     * @apiGroup 02-pick-deliver
     */

    /** 
     * @api {post} /ziplocker/getPrintList 06-getPrintList
     * @apiDescription 获取用户在该机器上的可打印的数据列表
     * @apiName getPrintList
     * @apiUse getPrintList
     * @apiGroup 02-pick-deliver
     */

    /** 
     * @api {post} /ziplocker/preAuthForDeliver 07-preAuthForDeliver
     * @apiDescription 在机器上请求下单
     * @apiName preAuthForDeliver
     * @apiUse preAuthForDeliver
     * @apiGroup 02-pick-deliver
     */

    /** 
     * @api {post} /ziplocker/getDeliverList 08-getDeliverList
     * @apiDescription 获取相关订单box数据
     * @apiName getDeliverList
     * @apiUse getDeliverList
     * @apiGroup 02-pick-deliver
     */

    /**
     * @api {post} /ziplocker/getDeliver 09-getDeliver
     * @apiDescription 获取相关订单
     * @apiName getDeliver
     * @apiUse getDeliver
     * @apiGroup 02-pick-deliver
     */

    public function __construct() {
        
        parent::__construct('ziplocker');
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

    /**
     * @apiDefine getBoxConfig
     * @apiParam {String} accessToken
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                      
            '1' => 'invalid accessToken',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.boxConfig
     * @apiSuccess {String}   data.boxConfig.cabinetId
     * @apiSuccess {String}   data.boxConfig.address
     * @apiSuccess {String}   data.boxConfig.zipcode
     * @apiSuccess {Object[]}   data.boxConfig.cabinets
     * @apiSuccess {Object}       data.boxConfig.cabinets.cabinet
     * @apiSuccess {String}         data.boxConfig.cabinets.cabinet.bodyId
     * @apiSuccess {String}         data.boxConfig.cabinets.cabinet.cabinetType main/sub
     * @apiSuccess {String}         data.boxConfig.cabinets.cabinet.model
     * @apiSuccess {String}         data.boxConfig.cabinets.cabinet.lockAddr
     * @apiSuccess {Object[]}         data.boxConfig.cabinets.cabinet.boxes
     * @apiSuccess {Object}             data.boxConfig.cabinets.cabinet.boxes.box
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.boxId
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.model
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.length
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.width
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.height
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.boxAddr
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.isAllocable
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.row
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.column
     * @apiSuccess {String}               data.boxConfig.cabinets.cabinet.boxes.box.blocked
     * @apiSuccessExample {json} Success-Response:
     *
        {
          "ret": 0,
          "msg": "Success",
          "data": {
            "boxConfig": {
              "cabinetId": "10001",
              "address": "WUHAN.China",
              "zipcode": "456890",
              "cabinets": [
                {
                    "cabinetType": "main",
                    "width": 39,
                    "height": 184
                },
                {
                  "bodyId": "10021",
                  "cabinetType": "sub",
                  "model": "10001",
                  "lockAddr": "00",
                  "boxes": [
                    {
                      "boxId": "10361",
                      "boxAddr": "00",
                      "isAllocable": "1",
                      "row": "1",
                      "column": "1",
                      "blocked": "0",
                      "model": "10001",
                      "length": "100.00",
                      "width": "80.00",
                      "height": "50.00"
                    },
                    {
                      "boxId": "10362",
                      "boxAddr": "01",
                      "isAllocable": "1",
                      "row": "2",
                      "column": "1",
                      "blocked": "0",
                      "model": "10001",
                      "length": "100.00",
                      "width": "80.00",
                      "height": "50.00"
                    },
                    {
                      "boxId": "10363",
                      "boxAddr": "02",
                      "isAllocable": "1",
                      "row": "3",
                      "column": "1",
                      "blocked": "0",
                      "model": "10001",
                      "length": "100.00",
                      "width": "80.00",
                      "height": "50.00"
                    },
                    {
                      "boxId": "10364",
                      "boxAddr": "03",
                      "isAllocable": "1",
                      "row": "4",
                      "column": "1",
                      "blocked": "0",
                      "model": "10001",
                      "length": "100.00",
                      "width": "80.00",
                      "height": "50.00"
                    },
                  ],
                },
              ],
            },
          },
        },
     *
     * @sendSampleRequest
     */

    //http://www.en.unibox.com.cn/cabinet/ziplocker/initBoxConfig?accessToken=7a5596563c59dda42a8f4333ae7c5b50
    public function initBoxConfig() {
        parent::initBoxConfig();
    }

    public function getBoxConfig() {
        parent::getBoxConfig();
    }

    /**
     * @apiDefine getBoxModelList
     * @apiParam {String}   accessToken

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'invalid accesstoken',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.boxModelList
     * @apiSuccess {Object} data.boxModelList.boxModel
     * @apiSuccess {String}   data.boxModelList.boxModel.boxModelName 箱子模型名称，eg. large, middle, small...
     * @apiSuccess {String}   data.boxModelList.boxModel.boxModelId 箱子模型的Id，用于insertDeliver时作为参数
     * @apiSuccess {String}   data.boxModelList.boxModel.boxModelPrice eg. 1.00, 2.00, ... 不同箱子的价格
     * @apiSuccess {String}   data.boxModelList.boxModel.boxModelCount 本快递柜上统计该boxModel的当前可用数量
     * @apiSuccess {String}   data.boxModelList.boxModel.length
     * @apiSuccess {String}   data.boxModelList.boxModel.width
     * @apiSuccess {String}   data.boxModelList.boxModel.height
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Box Model List Success",
     *     "data": {
     *         "boxModelList": [
     *             {
     *                 "boxModelId": "10001",
     *                 "boxModelName": "Small",
     *                 "boxModelPrice": 0,
     *                 "boxModelCount": 87,
     *                 "length": "100.00",
     *                 "width": "80.00",
     *                 "height": "50.00"
     *             },
     *             {
     *                 "boxModelId": "10002",
     *                 "boxModelName": "Middle",
     *                 "boxModelPrice": 0,
     *                 "boxModelCount": 0,
     *                 "length": "100.00",
     *                 "width": "80.00",
     *                 "height": "30.00"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getBoxModelList() {

        parent::getBoxModelList();
    }

    /**
     * @apiDefine preAuthForBox
     * @apiParam {String} accessToken
     * @apiParam {String} boxModelId      选择箱体类型
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForBox success',                                      
            '1' => 'invalid accesstoken',                                      
            '4' => 'empty box model id',                                                       
            '6' => 'fail to assign box',                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.box
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForDeliver
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.isAllocable
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "PreAuthForStore Success",
     *     "data": {
     *         "box": {
     *             "boxId": "10001",
     *             "lockAddr": "3",
     *             "boxAddr": "12",
     *             "isAllocable": "1"
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function preAuthForBox() {

        $boxModelId    = I('request.boxModelId');

        if(empty($boxModelId)) { $this->ret(4);}

        $boxId = D('CabinetBox')->assignBox($this->_cabinetId, $boxModelId, False);
        if(empty($boxId)) { $this->ret(6);}

        $box = D('CabinetBox')->getBodyBox($boxId);
        $boxModel = D('CabinetBoxModel')->getByModelId($boxModelId);


        $this->ret(0, [
            'box' => [
                'boxId' => $boxId,
                'lockAddr' => $box['lock_addr'],
                'boxAddr' => $box['box_addr'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }

    /**
     * @apiDefine blockBox
     * @apiParam {String} accessToken
     * @apiParam {String} boxId
     *
     * @apiSuccess {Number} ret
            '0' => 'block box success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty boxId',                                       
            '3' => 'block box fail',                                       
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "block box success",
          "data": {}
      }
     *
     *
     * @sendSampleRequest
     */
    public function blockBox() {

        parent::blockBox();
    }

    /**
     * @apiDefine getAppQRUrl
     * @apiParam {String} accessToken
     *
     * @apiSuccess {Number} ret
            '0' => 'getAppQRUrl success',                                                     
            '1' => 'invalid accesstoken',                                                      
            '2' => 'fail to get QRCode',                                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.sceneId
     * @apiSuccess {String}   data.url
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getAppQRUrl success",
          "data": {
            "sceneId": "unibox_qrcodetmp_member_1482221633_808302",
            "url": "http://www.en.unibox.com.cn/cabinet/qrCode/show?f=865dd5c40b2e690337dfb6f0ca06d436"
          }
        }
     *
     * @sendSampleRequest
     */
    public function getAppQRUrl() {
        parent::getAppQRUrl();
    }

    /**
     * @apiDefine getAppScanResult
     * @apiParam {String} accessToken
     * @apiParam {String} sceneId
     *
     * @apiSuccess {Number} ret
            '0' => 'getAppScanResult success',                                                
            '1' => 'invalid accesstoken',                                                     
            '2' => 'empty sceneId',                                                           
            '4' => 'QRCode has been expired',                                                 
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.email
     * @apiSuccess {String}     data.member.status
     * @apiSuccess {String}     data.member.money
     * @apiSuccess {String}     data.member.frozenMoney
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getAppScanResult success",
          "data": {
            "member": {
              "memberId": "10009",
              "email": "liuyuan@unibox.com.cn",
              "status": 0,
              "money": "190.00",
              "frozenMoney": "0.00",
            }
          }
     *
     * @sendSampleRequest
     */

    public function getAppScanResult() {
        parent::getAppScanResult();
    }

    /**
     * @apiDefine getDeliverList
     * @apiParam {String} accessToken
     * @apiParam {String} memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'getDeliverList success',                                                
            '1' => 'invalid accesstoken',                                                     
            '2' => 'empty memberId',                                                           
            '3' => 'no matched orders',                  
            '4' => 'wallet not enough, please recharge and try again',                  
            '5' => 'you need to bind a credit card to your account',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.deliverList
     * @apiSuccess {Object}     data.deliverList.deliver
     * @apiSuccess {String}     data.deliverList.deliver.lockAddr
     * @apiSuccess {String}     data.deliverList.deliver.boxAddr
     * @apiSuccess {String}     data.deliverList.deliver.isAllocable
     * @apiSuccess {String}     data.deliverList.deliver.deliverId        订单ID，用于commitForDeliver
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getDeliverList success",
          "data": {
            "deliverList": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "isAllocable" : "1",
                "deliverId" : "10001",
              },
              {
                "lockAddr" : "00",
                "boxAddr" : "02",
                "isAllocable" : "1",
                "deliverId" : "10002",
              }
            ]
          }
     *
     * @sendSampleRequest
     */

    public function getDeliverList() {

        $memberId = I('post.memberId');
        if (!$memberId) { $this->ret(2);}

        $res['deliverList']    = $this->getDelivers($memberId);

        if(empty($res['deliverList'])) {
            $this->ret(3);
        }

        if(!D('Wallet')->checkWallet($memberId)) {
            if(!D('CardCredit')->checkCard($memberId)) {
                $this->ret(5);
            } else {
                $this->ret(4);
            }
        }

        $this->ret(0, $res);
    }

    /**
     * @apiDefine getDeliver
     * @apiParam {String} accessToken
     * @apiParam {String} deliverId
     *
     * @apiSuccess {Number} ret
            '0' => 'getDeliver success',                                                
            '1' => 'invalid accesstoken',                                                     
            '2' => 'empty deliverId',                                                           
            '3' => 'no matched orders',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.deliver
     * @apiSuccess {String}     data.deliver.deliverId
     * @apiSuccess {String}     data.deliver.canPick     当快递员取件时请求结果如果为1方可开门，为0不可开门
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getDeliver success",
          "data": {
            "deliver": {
                'deliverId': 10050,
                'status': 0,
                'canPick': 0,
            }
          }
     *
     * @sendSampleRequest
     */

    public function getDeliver() {

        $deliverId = I('post.deliverId');
        if (!$deliverId) { $this->ret(2);}

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) {
            $this->ret(3);
        }

        $res['deliver']    = [
            'deliverId' => $deliverId,
            'status' => $deliver['status'],
            'canPick' => $deliver['status'] == C('z_deliver_status_code.token_success') ? 1 : 0,
        ];
        $this->ret(0, $res);
    }

    /**
     * @apiDefine resendPickCode
     * @apiParam {String} accessToken
     * @apiParam {String} [phone] phone, email 不可同时为空
     * @apiParam {String} [email] phone, email 不可同时为空
     *
     * @apiSuccess {Number} ret
            '0' => 'resend pick code success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty phone or email',                                       
            '3' => 'no matched orders found',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "resend pick code success",
          "data": {}
      }
     *
     *
     * @sendSampleRequest
     */
    public function resendPickCode() {

        $phone = I('post.phone');
        $email = I('post.email');

        if ($phone) {

            $delivers = $this->getDelivers(null, null, null, $phone);
            //TODO: resend phone msg
        } else if ($email) {

            $delivers = $this->getDelivers(null, null, null, null, $email);
            //TODO: resend email msg
        } else {
            $this->ret(2);
        }

        if(empty($delivers)) $this->ret(3);

        $this->ret(0);
    }

    /**
     * @apiDefine proveCode
     * @apiParam {String} accessToken
     * @apiParam {String} codeType  eg. pick/barcode
     * @apiParam {String} code
     *
     * @apiSuccess {Number} ret
            '0' => 'prove code success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty code or wrong codeType',                                      
            '3' => 'no matched pick orders',                                      
            '4' => 'wallet not enough, please recharge and try again.',                  
            '5' => 'you need to bind a credit card to your account',                  
            '6' => 'no matched orders for your parcel',                                      
            '7' => 'you need to register a member and bind a credit card to pay the storeFee',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.delivers
     * @apiSuccess {Object}     data.delivers.deliver
     * @apiSuccess {String}     data.delivers.deliver.lockAddr
     * @apiSuccess {String}     data.delivers.deliver.boxAddr
     * @apiSuccess {String}     data.delivers.deliver.isAllocable
     * @apiSuccess {String}     data.delivers.deliver.deliverId        订单ID，用于commitForDeliver
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "proveCode success",
          "data": {
            "delivers": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "isAllocable" : "1",
                "deliverId" : "10001",
              },
              {
                "lockAddr" : "00",
                "boxAddr" : "02",
                "isAllocable" : "1",
                "deliverId" : "10002",
              }
            ]
          }
      }
     *
     *
     * @sendSampleRequest
     */
    public function proveCode() {

        $code = I('post.code', '', 'trim');
        $codeType = I('post.codeType', '', 'trim');

        if (empty($code) || empty($codeType) || !in_array($codeType, [
            'pick',
            'barcode',
        ])) { $this->ret(2);}

        $res['delivers'] = $this->getDelivers(null, $code, $codeType);
        if(empty($res['delivers'])) $this->ret(6);

        if($codeType == 'pick') {

            $deliver = D('ZDeliver')->getByDeliverId($res['delivers'][0]['deliverId']);
            $memberId = $deliver['to_member_id'];

            if($memberId) {

                if(!D('Wallet')->checkWallet($memberId)) {
                    if(!D('CardCredit')->checkCard($memberId)) {
                        $this->ret(5);
                    } else {
                        $this->ret(4);
                    }
                }
            } else {

                $this->ret(7);
            }
        }

        $this->ret(0, $res);
    }

    /**
     * @apiDefine commitForDeliver
     * @apiParam {String} accessToken
     * @apiParam {String} deliverId   订单Id
     * @apiParam {String} boxId       当前操作的boxId
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForDeliver success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty deliver id',                           
            '3' => 'no matched order found by this id',                           
            '4' => 'wrong order status',                           
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForDeliver Success",
     * }
     * @sendSampleRequest
     */
    public function commitForDeliver() {

        $deliverId         = I('request.deliverId');
        $boxId             = I('request.boxId');

        if(empty($deliverId))       { $this->ret(2);}

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);

        if(empty($deliver)) {
            $this->ret(3);
        }

        //TODO
        // update box.status

        switch($deliver['status']) {
            case C('z_deliver_status_code.order_success'):
                if($deliver['from_cabinet_id'] != $this->_cabinetId) {
                    $this->ret(3);
                }
                $newStatus = 'store_success';
                //if boxId not reserved boxId, release reserved boxId
                if($boxId && $boxId != $deliver['from_box_id']) {
                    D('CabinetBox')->releaseBox($deliver['from_box_id'], $this->_cabinetId);
                    D('CabinetBox')->occupyBox($boxId);
                    $extraArr = [
                        'from_box_id' => $boxId,
                    ];
                }
                break;
            case C('z_deliver_status_code.store_success'):
                //ziplocker 自存自取订单 来取
                if($deliver['from_cabinet_id'] == $this->_cabinetId && $deliver['from_cabinet_id'] == $deliver['to_cabinet_id']) {
                    $newStatus = 'pick_success';
                } else {
                    $this->ret(3);
                }
                break;
            case C('z_deliver_status_code.token_success'):
                if($deliver['from_cabinet_id'] != $this->_cabinetId) {
                    $this->ret(3);
                }
                $newStatus = 'fetch_success';

                //update courier order status when courier fetch
                $order = D('ZCourierOrder')->getCourierOrder([
                    'deliver_id' => $deliverId,
                    'status' => $deliver['status'],
                ]);
                D('ZCourierOrder')->updateOrderStatus($order['order_id'], $newStatus, [
                    'fetch_time' => time(),
                ]);

                //release box after courier fetch success
                D('CabinetBox')->releaseBox($deliver['from_box_id'], $this->_cabinetId);
                break;
            case C('z_deliver_status_code.fetch_success'):
                if($deliver['to_cabinet_id'] != $this->_cabinetId) {
                    $this->ret(3);
                }
                $newStatus = 'deliver_success';
                //if boxId not reserved boxId, release reserved boxId
                if($boxId && $boxId != $deliver['to_box_id']) {
                    D('CabinetBox')->releaseBox($deliver['to_box_id'], $this->_cabinetId);
                    D('CabinetBox')->occupyBox($boxId);
                    $extraArr = [
                        'to_box_id' => $boxId,
                    ];
                }

                //update courier order status when courier deliver
                $order = D('ZCourierOrder')->getCourierOrder([
                    'deliver_id' => $deliverId,
                    'status' => $deliver['status'],
                ]);
                D('ZCourierOrder')->updateOrderStatus($order['order_id'], $newStatus, [
                    'deliver_time' => time(),
                ]);
                break;
            case C('z_deliver_status_code.deliver_success'):
                if($deliver['to_cabinet_id'] != $this->_cabinetId) {
                    $this->ret(3);
                }
                // update deliver with pick_time, pick_with
                $newStatus = 'pick_success';
                $extraArr = [
                    'pick_time' => $now,
                    'pick_with' => 'app',
                    //'pick_fee' =>
                ];

                //update courier order status when member pick
                $order = D('ZCourierOrder')->getCourierOrder([
                    'deliver_id' => $deliverId,
                    'status' => $deliver['status'],
                ]);
                D('ZCourierOrder')->updateOrderStatus($order['order_id'], $newStatus);

                //release box after member pick success
                D('CabinetBox')->releaseBox($deliver['to_box_id'], $this->_cabinetId);
                break;
            default:
                $this->ret(4);
        }

        D('ZDeliver')->updateDeliverStatus($deliverId, $newStatus, $extraArr);

        $this->ret(0);
    }

    /**
     * @apiDefine getPrintList
     * @apiParam {String} accessToken
     * @apiParam {String} memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'get print list success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty memberId',                    
            '3' => 'no matched deliver orders found',                       
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.printList
     * @apiSuccess {Object}     data.printList.print
     * @apiSuccess {String}     data.printList.print.cargoCode  货物码（用于生产条形码）
     * @apiSuccess {String}     data.printList.print.toCabinetId 目的地快递柜ID
     * @apiSuccess {String}     data.printList.print.toCabinetAddress 目的地快递柜地址
     * @apiSuccess {String}     data.printList.print.toName 收件人姓名
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "get print list success",
          "data": {
              "printList": [
                {
                  "cargoCode": "675334",
                  "toCabinetId": "10100",
                  "toCabinetAddress": "N10100, Austin VBStreet No23, Apt.303, Austin City,Texas, 232100",
                  "toName": null
                }
              ]
          }
      }
     *
     *
     * @sendSampleRequest
     */
    public function getPrintList() {

        $memberId = I('post.memberId');

        if (empty($memberId)) {
            $this->ret(2);
        }

        $res['printList'] = $this->getPrintData($memberId);
        if(empty($res['printList'])) $this->ret(3);

        //TODO: update order print counter
        $this->ret(0, $res);
    }

    /**
     * @apiDefine preAuthForDeliver
     * @apiParam {String} accessToken
     * @apiParam {String} memberId
     * @apiParam {String} boxModelId 箱体型号ID
     * @apiParam {String} [toZipcode] 目的地zipcode
     * @apiParam {String} [toPhone]   收件人phone 和 email不能同时为空
     * @apiParam {String} [toEmail]   收件人phone 和 email不能同时为空
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForDeliver success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty box model id',                                                       
            '3' => 'empty destination zipcode',                                                       
            '4' => 'empty picker\'s phone or email',             
            '5' => 'empty member id',                           
            '6' => 'fail to assign box for your deliver',                      
            '7' => 'no matched ziplocker found by the zipcode',                      
            '8' => 'fail to assign box for your deliver in destination ziplocker',                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.email
     * @apiSuccess {String}     data.member.status
     * @apiSuccess {String}     data.member.money
     * @apiSuccess {String}     data.member.frozenMoney
     * @apiSuccess {Object[]} [data.delivers] 自存自取，客户端需要根据data.delivers直接让用户开箱
     * @apiSuccess {Object}     data.delivers.deliver
     * @apiSuccess {String}     data.delivers.deliver.lockAddr
     * @apiSuccess {String}     data.delivers.deliver.boxAddr
     * @apiSuccess {String}     data.delivers.deliver.isAllocable
     * @apiSuccess {String}     data.delivers.deliver.deliverId        订单ID，用于commitForDeliver
     * @apiSuccess {Object[]} [data.printList] 同城快递，客户端需要根据data.printList打印货物条形码
     * @apiSuccess {Object}     data.printList.print
     * @apiSuccess {String}     data.printList.print.cargoCode  货物码（用于生产条形码）
     * @apiSuccess {String}     data.printList.print.toCabinetId 目的地快递柜ID
     * @apiSuccess {String}     data.printList.print.toCabinetAddress 目的地快递柜地址
     * @apiSuccess {String}     data.printList.print.toName 收件人姓名
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "preAuthForDeliver success",
          "data": {
              "member": {
                "memberId": "20091",
                "email": "",
                "status": 0,
                "money": "0.00",
                "frozenMoney": "0.00"
              },
              "delivers": [
                {
                  "lockAddr": "2",
                  "boxAddr": "6",
                  "isAllocable": "1",
                  "deliverId": "10039",
                }
              ],
              "printList": [
                {
                  "cargoCode": "675334",
                  "toCabinetId": "10100",
                  "toCabinetAddress": "N10100, Austin VBStreet No23, Apt.303, Austin City,Texas, 232100",
                  "toName": null
                }
              ]
          }
     *
     * @sendSampleRequest
     */
    public function preAuthForDeliver() {

        $memberId      = I('request.memberId');
        $boxModelId    = I('request.boxModelId');
        $toZipcode     = I('request.toZipcode');
        $toPhone       = I('request.toPhone');
        $toEmail       = I('request.toEmail');

        if(empty($boxModelId)) { $this->ret(2);}
        //if(empty($toZipcode))  { $this->ret(3);}
        if($toPhone) {
            $toMember = D('Member')->getMemberByPhone($toPhone);
        } else if($toEmail) {
            $toMember = D('Member')->getMemberByPhone($toEmail);
        } else {
            $this->ret(4);
        }
        if(empty($memberId)) { $this->ret(5);}

        $fromCabinetId = $this->_cabinetId;

        $fromBoxId = D('CabinetBox')->assignBox($fromCabinetId, $boxModelId);
        if(empty($fromBoxId)) { $this->ret(6);}

        if(empty($toZipcode) || (!empty($toZipcode) && $toZipcode == $this->_zipcode)) {
            //store and pick
            $toCabinetId = $fromCabinetId;
            $toBoxId = $fromBoxId;
        } else {
            //deliver and pick

            $toCabinet = D('Cabinet')->getCabinetByZipcode($toZipcode);
            if($toCabinet) {
                $toCabinetId = $toCabinet['cabinet_id'];
            } else {
                $this->ret(7);
            }

            $toBoxId = D('CabinetBox')->assignBox($toCabinetId, $boxModelId);
            if(empty($toBoxId)) { $this->ret(8);}
        }

        $pickCode = \Org\Util\String::randString(6, 2);
        $cargoCode = mt_rand(100000, 999999);

        $price = D('ZDeliver')->calDeliverPrice([
            'fromCabinetId' => $fromCabinetId,
            'toCabinetId' => $toCabinetId,
            'boxModelId' => $boxModelId,
        ]);

        $deliver = array(
            #'deliver_id' =>
            #'cargo_type_id' => ['exp', 'null'],
            #'cargo_worth' => ['exp', 'null'],
            #'cargo_weight' => ['exp', 'null'],
            'box_model_id' => $boxModelId,

            'from_member_id' => $memberId,
            'from_zipcode' => $this->_zipcode,
            'from_cabinet_id' => $fromCabinetId,
            'from_box_id' => $fromBoxId,

            'to_member_id' => $toMember ? $toMember['member_id'] : array('exp', 'null'),
            'to_email' => $toEmail ? : ['exp', 'null'],
            'to_phone' => $toPhone ? : ['exp', 'null'],
            #'to_name' => ['exp', 'null'],
            'to_zipcode' => $toZipcode ? : $this->_zipcode,
            'to_cabinet_id' => $toCabinetId,
            'to_box_id' => $toBoxId,

            #'deliver_photo_group_id' => ['exp', 'null'],
            'dist' => D('Cabinet')->getDistance($fromCabinetId, $toCabinetId),

            'fee_dist' => $price['distFee'],
            'fee_box' => $price['boxFee'],
            'fee_total' => $price['totalFee'],
            #'remark' =>
            'cargo_code' => $cargoCode,
            'pick_code' => $pickCode,//generated

            'create_time' => time(),
            'order_time' => time(),//bugfix: order on ziplocker must set order_time when insertDeliver
            #'update_time' =>

            'status' => C('z_deliver_status_code.order_success'),
            #'courier_id' =>
        );

        $deliverId = D('ZDeliver')->insertDeliver($deliver);

        //TODO: release box if insertDeliver fail

        $res['member']   = $this->getMember($memberId);
        if($toCabinetId == $fromCabinetId) {
            $res['delivers']  = $this->getDelivers(null, $cargoCode, 'barcode');
        } else {
            $res['printList']    = $this->getPrintData($memberId, $deliverId);
        }

        $this->ret(0, $res);
    }

    private function getDelivers($memberId, $code, $codeType, $phone, $email) {


        if($code) {
            if($codeType == 'pick') {//pick
                $wh = [
                    'pick_code' => $code,
                    'to_cabinet_id' => $this->_cabinetId,
                    'status' => C('z_deliver_status_code.deliver_success')
                ];
            } else if($codeType == 'barcode') {

                $wh = $where = [];
                $where = [
                        //store
                    [
                        'to_cabinet_id' => $this->_cabinetId,
                        'status' => C('z_deliver_status_code.fetch_success')
                    ], [
                        //deliver
                        'from_cabinet_id' => $this->_cabinetId,
                        'status' => C('z_deliver_status_code.order_success')
                    ],
                    '_logic' => 'or'
                ];

                $wh['_complex'] = $where;
                $wh['cargo_code'] = $code;
            } else {
                return [];
            }
        } else if($memberId) {

            $wh = $where = [];
            $where = [
                [
                    //fetch
                    'courier_id' => $memberId,
                    'from_cabinet_id' => $this->_cabinetId,
                    'status' => C('z_deliver_status_code.token_success')
                ], [
                    //pick (different cabinet)
                    'to_member_id' => $memberId,
                    'to_cabinet_id' => $this->_cabinetId,
                    'from_cabinet_id' => ['neq', $this->_cabinetId],
                    'status' => ['in', [
                        C('z_deliver_status_code.deliver_success'),
                    ]],
                ], [
                    //pick (same cabinet)
                    'to_member_id' => $memberId,
                    'to_cabinet_id' => $this->_cabinetId,
                    'from_cabinet_id' => $this->_cabinetId,
                    'status' => ['in', [
                        C('z_deliver_status_code.store_success'),
                    ]],
                ],
                '_logic' => 'or'
            ];

            $wh['_complex'] = $where;
        } else if($phone) {

            $wh = [
                //pick
                'to_phone' => $phone,
                'to_cabinet_id' => $this->_cabinetId,
                'status' => C('z_deliver_status_code.deliver_success')
            ];
        } else if($email) {

            $wh = [
                //pick
                'to_email' => $email,
                'to_cabinet_id' => $this->_cabinetId,
                'status' => C('z_deliver_status_code.deliver_success')
            ];
        } else {
            return [];
        }

        $deliverArr = array();

        $deliverList= D('ZDeliver')->getDeliverList($wh);
        foreach($deliverList as $d) {
            switch($d['status']) {
                case C('z_deliver_status_code.order_success'):
                    $box = D('CabinetBox')->getBodyBox($d['from_box_id']);
                    break;
                case C('z_deliver_status_code.store_success'):
                    $box = D('CabinetBox')->getBodyBox($d['to_box_id']);
                    break;
                case C('z_deliver_status_code.token_success'):
                    $box = D('CabinetBox')->getBodyBox($d['from_box_id']);
                    break;
                case C('z_deliver_status_code.fetch_success'):
                    $box = D('CabinetBox')->getBodyBox($d['to_box_id']);
                    break;
                case C('z_deliver_status_code.deliver_success'):
                    $box = D('CabinetBox')->getBodyBox($d['to_box_id']);
                    break;
                default:
                    return [];
            }
            $deliverArr[] = [
                'lockAddr' => $box['lock_addr'],
                'boxAddr' => $box['box_addr'],
                'isAllocable' => $box['is_allocable'],
                'deliverId' => $d['deliver_id'],
            ];
        }
        return $deliverArr;
    }

    private function getPrintData($memberId, $deliverId) {

        if($memberId) {
            $wh['from_member_id'] = $memberId;
        } else {
            return [];
        }

        if($deliverId) {
            $wh['deliver_id'] = $deliverId;
        }

        $wh['from_cabinet_id'] = $this->_cabinetId;

        $deliverArr = array();

        $deliverList= D('ZDeliver')->getDeliverList(array_merge($wh, [
            'from_box_id' => array('exp', 'is not null'),
            'status' => C('z_deliver_status_code.order_success'),
        ]));
        foreach($deliverList as $d) {
            //only deliver order print
            if($d['to_cabinet_id'] != $d['from_cabinet_id']) {
                $cabinet = D('Cabinet')->getCabinet($d['to_cabinet_id']);
                $deliverArr[] = [
                    'cargoCode' => $d['cargo_code'],
                    'toCabinetId' => $d['to_cabinet_id'],
                    'toCabinetAddress' => $cabinet['address'],
                    'toName' => $d['to_name'],
                ];
            }
        }
        return $deliverArr;
    }
}
