<?php
namespace Cabinet\Controller;
use Think\Controller;
use Common\Common;

class ZipporaController extends BaseController {

    /**
     * @api {post} /zippora/initData initData
     * @apiName initData
     * @apiUse initData
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/updateSequence updateSequence
     * @apiName updateSequence
     * @apiUse updateSequence
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getAdminCardList getAdminCardList
     * @apiName getAdminCardList
     * @apiUse getAdminCardList
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
     * @api {post} /zippora/getBoxConfig getBoxConfig
     * @apiDescription 初始化box数据
     * @apiName getBoxConfig
     * @apiUse getBoxConfig
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getBoxModelList getBoxModelList
     * @apiDescription 获取箱体类型数据
     * @apiName getBoxModelList
     * @apiUse getBoxModelList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getBuildingList getBuildingList[弃用]
     * @apiDescription 获取公寓内楼栋数据
     * @apiName getBuildingList
     * @apiUse getBuildingList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getRoomList getRoomList[弃用]
     * @apiDescription 获取某楼栋内房间配置数据
     * @apiName getRoomList
     * @apiUse getRoomList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getUnitList getUnitList
     * @apiDescription 获取某公寓内房间配置数据
     * @apiName getUnitList
     * @apiUse getUnitList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getRoomMemberList getRoomMemberList[弃用]
     * @apiDescription 获取某房间内用户信息列表
     * @apiName getRoomMemberList
     * @apiUse getRoomMemberList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/getUnitMemberList getUnitMemberList
     * @apiDescription 获取某房间内用户信息列表(包含Householder member的名字)
     * @apiName getUnitMemberList
     * @apiUse getUnitMemberList
     * @apiGroup 01-common
     */

    /**
     * @api {post} /zippora/blockBox blockBox
     * @apiDescription 设置某个Box状态为Blocked
     * @apiName blockBox
     * @apiUse blockBox
     * @apiGroup 01-common
     */
    /**
     * @api {post} /zippora/releaseblockBox releaseblockBox
     * @apiDescription 解除某个Box状态为Released
     * @apiName releaseblockBox
     * @apiUse releaseblockBox
     * @apiGroup 01-common
     */ 
    /**
     * @api {post} /zippora/reportError reportError
     * @apiName reportError
     * @apiUse reportError
     * @apiGroup 01-common
     */

    /** store
     * @api {post} /zippora/identifyCard 01-identifyCard
     * @apiDescription 识别快递员身份通过ID卡
     * @apiName identifyCard
     * @apiUse identifyCard
     * @apiGroup 03-store
     */

    /**
     * @api {post} /zippora/preAuthForStore 02-preAuthForStore[废弃]
     * @apiDescription 在机器上请求添加store订单
     * @apiName preAuthForStore
     * @apiUse preAuthForStore
     * @apiGroup 03-store
     */

    /**
     * @api {post} /zippora/preAuthForBox 03-preAuthForBox
     * @apiDescription 在机器上请求开箱(不下单)
     * @apiName preAuthForBox
     * @apiUse preAuthForBox
     * @apiGroup 03-store
     */

    /**
     * @api {post} /zippora/commitForStore 04-commitForStore
     * @apiDescription 在机器上请求完成store订单(快递员存件订单)
     * @apiName commitForStore
     * @apiUse commitForStore
     * @apiGroup 03-store
     */

    /**
     * @api {post} /zippora/commitForSelfStore 05-commitForSelfStore
     * @apiDescription 在机器上请求完成store订单(自存自取)
     * @apiName commitForSelfStore
     * @apiUse commitForSelfStore
     * @apiGroup 03-store
     */
     
    /**
     * @api {post} /zippora/identifyPackNo 06-identifyPackNo
     * @apiDescription 识别共享柜存包码是否可在当前柜存包
     * @apiName identifyPackNo
     * @apiUse identifyPackNo
     * @apiGroup 03-store
     */

    /** pick
     * @api {post} /zippora/getAppQRUrl 01-getAppQRUrl
     * @apiDescription 获取member身份验证二维码
     * @apiName getAppQRUrl
     * @apiUse getAppQRUrl
     * @apiGroup 02-pick
     */

    /**
     * @api {post} /zippora/getAppScanResult 02-getAppScanResult
     * @apiDescription 获取member身份验证扫码结果
     * @apiName getAppScanResult
     * @apiUse getAppScanResult
     * @apiGroup 02-pick
     */

    /**
     * @api {post} /zippora/resendPickCode 03-resendPickCode
     * @apiDescription 重发取件码（存件订单）
     * @apiName resendPickCode
     * @apiUse resendPickCode
     * @apiGroup 02-pick
     */

    /**
     * @api {post} /zippora/proveCode 04-proveCode
     * @apiDescription 验证取货码
     * @apiName proveCode
     * @apiUse proveCode
     * @apiGroup 02-pick
     */

    /**
     * @api {post} /zippora/commitForPick 05-commitForPick
     * @apiDescription 在机器上请求完成pick
     * @apiName commitForPick
     * @apiUse commitForPick
     * @apiGroup 02-pick
     */

    /**
     * @api {post} /zippora/getPickList 06-getPickList
     * @apiDescription 获取相关订单box数据
     * @apiName getPickList
     * @apiUse getPickList
     * @apiGroup 02-pick
     */

    /**
     * @api {post} /zippora/proveCodePickMart 07-proveCodePickMart
     * @apiDescription 验证取货码
     * @apiName proveCodePickMart
     * @apiUse proveCodePickMart
     * @apiGroup 02-pick
     */
  
    /**
     * @api {post} /zippora/commitForPickMart 08-commitForPickMart
     * @apiDescription 在机器上请求完成pick
     * @apiName commitForPickMart
     * @apiUse commitForPickMart
     * @apiGroup 02-pick
     */
     
    /**
     * @api {post} /zippora/getPickListPickMart 09-getPickListPickMart
     * @apiDescription 获取相关订单box数据
     * @apiName getPickListPickMart
     * @apiUse getPickListPickMart
     * @apiGroup 02-pick
     */


    /**
     * @api {post} /zippora/commitForOcStore 02-commitForOcStore
     * @apiDescription 在机器上请求完成oc store订单(存件订单)
     * @apiName commitForOcStore
     * @apiUse commitForOcStore
     * @apiGroup 04-oc_store
     */

    /**
     * @api {post} /zippora/getOcCustomerByOcOrderId 01-getOcCustomerByOcOrderId
     * @apiDescription 查询订单关联客户
     * @apiName getOcCustomerByOcOrderId
     * @apiUse getOcCustomerByOcOrderId
     * @apiGroup 04-oc_store
     */
 
    /**
     * @api {post} /zippora/getPlatformInfo 01-getPlatformInfo
     * @apiDescription 获取外部订单平台基本信息
     * @apiName getPlatformInfo
     * @apiUse getPlatformInfo
     * @apiGroup 05-platformstore
     */

    /**
     * @api {post} /zippora/getOrder 02-getOrder
     * @apiDescription 获取外部订单数据
     * @apiName getOrder
     * @apiUse getOrder
     * @apiGroup 05-platformstore
     */
     
    /**
     * @api {post} /zippora/getUser 01-getUser
     * @apiDescription 获取区域用户基本信息
     * @apiName getUser
     * @apiUse getUser
     * @apiGroup 06-domain
     */
     
    /**
     * @api {post} /zippora/commitForStoreShare 02-commitForStoreShare
     * @apiDescription 在机器上请求完成共享柜存包订单(快递员存件订单)
     * @apiName commitForStoreShare
     * @apiUse commitForStoreShare
     * @apiGroup 06-domain
     */
     
    /**
     * @api {post} /zippora/userLogin 03-userLogin
     * @apiDescription 在机器上使用邮件或UserId与密码登录
     * @apiName userLogin
     * @apiUse userLogin
     * @apiGroup 06-domain
     */

    /**
     * @api {post} /zippora/identifyCardForRent 01-identifyCardForRent
     * @apiDescription 识别租借人身份通过ID卡
     * @apiName identifyCardForRent
     * @apiUse identifyCardForRent
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/identifyCardForReturn 02-identifyCardForReturn
     * @apiDescription 识别产品信息身份通过ID卡
     * @apiName identifyCardForReturn
     * @apiUse identifyCardForReturn
     * @apiGroup 10-asset
     */
	 /** asset
     * @api {post} /zippora/identifyCardForReturnBarcode 021-identifyCardForReturnBarcode
     * @apiDescription 识别产品信息身份通过ID卡
     * @apiName identifyCardForReturnBarcode
     * @apiUse identifyCardForReturnBarcode
     * @apiGroup 10-asset
     */
	 /** asset
     * @api {post} /zippora/getProductInventoryList 03-getProductInventoryList
     * @apiDescription 得到产品列表
     * @apiName getProductInventoryList
     * @apiUse getProductInventoryList
     * @apiGroup 10-asset
     */	 
	 
	 /** asset
     * @api {post} /zippora/getProductInventoryListN 18-getProductInventoryListN
     * @apiDescription 得到产品列表
     * @apiName getProductInventoryListN
     * @apiUse getProductInventoryListN
     * @apiGroup 10-asset
     */
	 
	 /** asset
     * @api {post} /zippora/preAuthForBoxRent 04-preAuthForBoxRent
     * @apiDescription 租借前得到柜子
     * @apiName preAuthForBoxRent
     * @apiUse preAuthForBoxRent
     * @apiGroup 10-asset
     */	 

	 /** asset
     * @api {post} /zippora/getBoxInfoForRent 05-getBoxInfoForRent
     * @apiDescription 根据BOXID得到柜隔口硬件信息
     * @apiName getBoxInfoForRent
     * @apiUse getBoxInfoForRent
     * @apiGroup 10-asset
     */
	 
	 /** asset
     * @api {post} /zippora/commitForAssetRent 06-commitForAssetRent
     * @apiDescription 租借产品确认
     * @apiName commitForAssetRent
     * @apiUse commitForAssetRent
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/preAuthForBoxReturn 07-preAuthForBoxReturn
     * @apiDescription 归还前得到柜子
     * @apiName preAuthForBoxReturn
     * @apiUse preAuthForBoxReturn
     * @apiGroup 10-asset
     */
	 
	 /** asset
     * @api {post} /zippora/commitForAssetReturn 08-commitForAssetReturn
     * @apiDescription 归还产品确认
     * @apiName commitForAssetReturn
     * @apiUse commitForAssetReturn
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/commitForAssetReturnBarcode 08-commitForAssetReturnBarcode
     * @apiDescription 归还产品确认
     * @apiName commitForAssetReturnBarcode
     * @apiUse commitForAssetReturnBarcode
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/commitForAssetAdmin 13-commitForAssetAdmin
     * @apiDescription 归还产品确认
     * @apiName commitForAssetAdmin
     * @apiUse commitForAssetAdmin
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/proveCodeForAsset 14-proveCodeForAsset
     * @apiDescription 取件
     * @apiName proveCodeForAsset
     * @apiUse proveCodeForAsset
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/commitForAsset 15-commitForAsset
     * @apiDescription 取件
     * @apiName commitForAsset
     * @apiUse commitForAsset
     * @apiGroup 10-asset
     */
	/** asset
     * @api {post} /zippora/releaseBoxid 16-releaseBoxid
     * @apiDescription 取件
     * @apiName releaseBoxid
     * @apiUse releaseBoxid
     * @apiGroup 10-asset
     */ 
	 /** asset
     * @api {post} /zippora/preAuthForAdmin 09-preAuthForAdmin
     * @apiDescription 归还前得到柜子
     * @apiName preAuthForAdmin
     * @apiUse preAuthForAdmin
     * @apiGroup 10-asset
     */
	 
	 /** asset
     * @api {post} /zippora/getProductList 10-getProductList
     * @apiDescription 得到产品列表
     * @apiName getProductList
     * @apiUse getProductList
     * @apiGroup 10-asset
     */	
	 
	 /** asset
     * @api {post} /zippora/getProductListN 10-getProductListN
     * @apiDescription 得到产品列表
     * @apiName getProductListN
     * @apiUse getProductListN
     * @apiGroup 10-asset
     */	
	 
	 /** asset
     * @api {post} /zippora/getProductInfo 11-getProductInfo
     * @apiDescription 得到产品列表
     * @apiName getProductInfo
     * @apiUse getProductInfo
     * @apiGroup 10-asset
     */

	 /** asset
     * @api {post} /zippora/getBoxModelFromRfid 12-getBoxModelFromRfid
     * @apiDescription 得到产品列表
     * @apiName getBoxModelFromRfid
     * @apiUse getBoxModelFromRfid
     * @apiGroup 10-asset
     */
	 
	 /** asset
     * @api {post} /zippora/getCategoryList 17-getCategoryList
     * @apiDescription 得到产品类别列表
     * @apiName getCategoryList
     * @apiUse getCategoryList
     * @apiGroup 10-asset
     */	
	 
	 /** asset
     * @api {post} /zippora/getCategoryListN 17.1-getCategoryListN
     * @apiDescription 得到产品类别列表
     * @apiName getCategoryListN
     * @apiUse getCategoryListN
     * @apiGroup 10-asset
     */	 
     
     /** asset
     * @api {post} /zippora/getApartmentId 19-getApartmentId
     * @apiDescription 得到organizationid
     * @apiName getApartmentId
     * @apiUse getApartmentId
     * @apiGroup 10-asset
     */	
	 
	 /** asset
     * @api {post} /zippora/commitForAssetRentN 20-commitForAssetRentN
     * @apiDescription 操作员重置
     * @apiName commitForAssetRentN
     * @apiUse commitForAssetRentN
     * @apiGroup 10-asset
     */
    
	/**
     * @api {post} /zippora/testa 01-testa
     * @apiDescription test1
     * @apiName testa
     * @apiUse testa
     * @apiGroup 14-admin 
     */
    public function __construct() {
        
        parent::__construct('zippora');
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

    //http://www.en.unibox.com.cn/cabinet/api/initBoxConfig?accessToken=7a5596563c59dda42a8f4333ae7c5b50
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
     * @apiDefine getBuildingList
     * @apiParam {String}   accessToken

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'invalid accesstoken',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.buildingList
     * @apiSuccess {Object}     data.buildingList.building
     * @apiSuccess {String}     data.buildingList.building.buildingId
     * @apiSuccess {String}     data.buildingList.building.buildingName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Building List Success",
     *     "data": {
     *         "buildingList": [
     *             {
     *                 "buildingId": "10001",
     *                 "buildingName": "A001"
     *             },
     *             {
     *                 "buildingId": "10002",
     *                 "buildingName": "A002"
     *             },
     *             {
     *                 "buildingId": "10003",
     *                 "buildingName": "A003"
     *             },
     *             {
     *                 "buildingId": "10004",
     *                 "buildingName": "A004"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getBuildingList() {

        $apartmentId = D('OOrganizationCabinet')->getFieldByCabinetId($this->_cabinetId, 'organization_id');
        $buildingArr = D('OBuilding')->getBuildingArr(['organization_id' => $apartmentId]);

        $data = [
            'buildingList' => array_values($buildingArr),
        ];

        $this->ret(0, $data);
    }

    /**
     * @apiDefine getRoomList
     * @apiParam {String}   accessToken
     * @apiParam {String}   buildingId 楼栋ID，请求某楼栋内房间配置数据，必填楼栋ID

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'invalid buildingId',                                           
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.roomList
     * @apiSuccess {Object}     data.roomList.room
     * @apiSuccess {String}     data.roomList.room.roomId
     * @apiSuccess {String}     data.roomList.room.roomName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Room List Success",
     *     "data": {
     *         "roomList": [
     *             {
     *                 "roomId": "10001",
     *                 "roomName": "1-101"
     *             },
     *             {
     *                 "roomId": "10002",
     *                 "roomName": "1-102"
     *             }
     *         ] 
     *     }
     * }
     * @apiSampleRequest
     */
    public function getRoomList() {

        $buildingId = I('request.buildingId');

        if(empty($buildingId)){
            $this->ret(2);
        }
        $roomArr = D('ORoom')->getRoomArr(['building_id'=>$buildingId]);
        $data = [
            'roomList' => array_values($roomArr),
        ];

        $this->ret(0, $data);
    }

    /**
     * @apiDefine getRoomMemberList
     * @apiParam {String}   accessToken
     * @apiParam {String}   [roomId] 房间ID，请求房间内用户数据，选填roomId, roomName不能同时为空
     * @apiParam {String}   [roomName] 房间编号，请求房间内用户数据，选填roomId, roomName不能同时为空

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty roomId or empty roomName',                                                      
            '3' => 'no member binded this room',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.memberList
     * @apiSuccess {Object}     data.memberList.member
     * @apiSuccess {String}     data.memberList.member.memberId
     * @apiSuccess {String}     data.memberList.member.memberName
     * @apiSuccess {String}     data.memberList.member.buildingId
     * @apiSuccess {String}     data.memberList.member.buildingName
     * @apiSuccess {String}     data.memberList.member.roomId
     * @apiSuccess {String}     data.memberList.member.roomName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Room Member List Success",
     *     "data": {
     *         "memberList": [
     *             {
                        "memberId": "10009",
                        "memberName": "ganiks liu",
                        "buildingId": "10003",
                        "buildingName": "A003",
                        "roomId": "10007",
                        "roomName": "2-102"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getRoomMemberList() {

        $roomId = I('request.roomId');
        $roomName = I('request.roomName');// roomName maybe a member name too

        if(empty($roomId) && empty($roomName)){
            $this->ret(2);
        }

        if($roomId) {
            $wh = ['o_room.room_id' => $roomId];
        } else {
            $where = [
                'o_room.room_name' => $roomName,
                'member_profile.first_name' => ['like', "%$roomName%"],
                'member_profile.last_name' => ['like', "%$roomName%"],
                '_logic' => 'or',
            ];
            $wh['_complex'] = $where;
        }
        $wh['o_member_organization.organization_id'] = $this->_apartmentId;
        $wh['o_member_organization.status'] = 1;//member must has paid
        $wh['o_member_organization.approve_status'] = 1;//member plan must has been approved
        $memberList = D('OMemberOrganization')->getMemberApartmentList($wh);
        $memberArr = [];

        if(empty($memberList)){
            $this->ret(3);
        }

        foreach($memberList as $k => $member) {
            $memberArr[] = [
                'memberId' => $member['member_id'],
                'memberName' => $member['first_name'].' '.$member['last_name'],
                'buildingId' => $member['building_id'],
                'buildingName' => $member['building_name'],
                'roomId' => $member['room_id'],
                'roomName' => $member['room_name'],
            ];
        }

        $data = [
            'memberList' => $memberArr,
        ];

        $this->ret(0, $data);
    }

    /**
     * @apiDefine getUnitList
     * @apiParam {String}   accessToken
     * @apiParam {String}   organizationId 公寓ID，请求某公寓内房间配置数据，必填公寓ID

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get unit list success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'invalid organizationId',                                           
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.unitList
     * @apiSuccess {Object}     data.unitList.unit
     * @apiSuccess {String}     data.unitList.unit.unitId
     * @apiSuccess {String}     data.unitList.unit.unitName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Unit List Success",
     *     "data": {
     *         "unitList": [
     *             {
     *                 "unitId": "10001",
     *                 "unitName": "1-101"
     *             },
     *             {
     *                 "unitId": "10002",
     *                 "unitName": "1-102"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getUnitList() {

        $apartmentId = I('request.organizationId');

        if(empty($apartmentId)){
            $this->ret(2);
        }
        $unitArr = D('OUnit')->getUnitArr();
        $data = [
            'unitList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }

    /**
     * @apiDefine getUnitMemberList
     * @apiParam {String}   accessToken
     * @apiParam {String}   [unitId] 房间ID，请求房间内用户数据(从房间列表中点击某个房间后请求memberlist用此参数)，选填unitId, unitName, 但不能同时为空
     * @apiParam {String}   [unitName] 房间编号/用户姓名/用户家庭成员姓名(直接模糊查找memberlist用此参数)，请求房间内用户数据，选填unitId, unitName, 但不能同时为空

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get member list success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty unit id or empty unit name',                                                      
            '3' => 'no member binded this unit',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.members
     * @apiSuccess {Object}     data.members.member
     * @apiSuccess {String}     data.members.member.memberId
     * @apiSuccess {String}     data.members.member.memberName 可能是家庭成员的姓名
     * @apiSuccess {String}     data.members.member.unitId
     * @apiSuccess {String}     data.members.member.unitName
     * @apiSuccess {String}     data.members.member.organizationId
     * @apiSuccess {String}     data.members.member.organizationName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Unit Member List Success",
     *     "data": {
     *         "memberList": [
     *             {
                        "memberId": "10009",
                        "memberName": "ganiks liu",
                        "unitId": "10007",
                        "unitName": "2-102"
                        "organizationId": "10001",
                        "organizationName": "SSS organization"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getUnitMemberList() {

        $unitId = I('request.unitId');
        $unitName = I('request.unitName');// unitName maybe a member name too

        if(empty($unitId) && empty($unitName)){
            $this->ret(2);
        }

        if($unitId) {
            $wh = ['o_unit.unit_id' => ['like', "%$unitId%"]];
        } else {
            $where = [
                'o_unit.unit_name' => ['like', "%$unitName%"],
                'member_profile.first_name' => ['like', "%$unitName%"],
                'member_profile.last_name' => ['like', "%$unitName%"],
                'member_profile.householder_member' => ['like', "%$unitName%"],
                '_logic' => 'or',
            ];
            $wh['_complex'] = $where;
        }
        $wh['o_member_organization.organization_id'] = $this->_apartmentId;
        $wh['o_member_organization.status'] = 1;//member must has paid
        $wh['o_member_organization.approve_status'] = 1;//member plan must has been approved
        $memberList = D('OMemberOrganization')->getMemberApartmentList($wh);
        $memberArr = [];

        if(empty($memberList)){
            $this->ret(3);
        }

        foreach($memberList as $k => $member) {
            if(strpos(strtolower($member['first_name'].' '.$member['last_name']), strtolower($unitName)) !== -1) {
                $memberArr[] = [
                    'memberId' => $member['member_id'],
                    'memberName' => $member['first_name'].' '.$member['last_name'],
                    'unitId' => $member['unit_id'],
                    'unitName' => $member['unit_name'],
                    'organizationId' => $member['organization_id'],
                    'organizationName' => $member['organization_name'],
                ];
            }
            //check member_profile householder_member, if exists, explode as members
            if($member['householder_member']) {
                foreach(explode(',', $member['householder_member']) as $name) {
                    if(strpos(strtolower($name), strtolower($unitName)) !== false) {
                        $memberArr[] = [
                            'memberId' => $member['member_id'],
                            'memberName' => $name,
                            'unitId' => $member['unit_id'],
                            'unitName' => $member['unit_name'],
                            'organizationId' => $member['organization_id'],
                            'organizationName' => $member['organization_name'],
                        ];
                    }
                }
            }
        }

        $data = [
            'memberList' => $memberArr,
        ];

        $this->ret(0, $data);
    }

    /**
     * @apiDefine identifyCard
     * @apiParam {String} accessToken
     * @apiParam {String} cardCode 快递员ID卡号码
     *
     * @apiSuccess {Number} ret
            '0' => 'identifyCard success',                                                     
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty card code',                           
            '3' => 'no matched courier found',                  
            '4' => 'you have not been authorized by this organization manager',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.courier
     * @apiSuccess {String}     data.courier.courierId
     * @apiSuccess {String}     data.courier.courierName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "courier": {
     *             "courierId": "10001",
     *             "courierName": "RobbinSun"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function identifyCard() {

        $cardCode = I('post.cardCode');
        if(empty($cardCode)) {
        $this->ret(2);
        }
        $courier = D('OCourier')->getByCardCode($cardCode);
        if(empty($courier)) 
        {
          $courier=D('OCourierCompanyOrganization')->getByAccessCode($cardCode);
          if(empty($courier)) 
          {
             $this->ret(3);
          }
          $courier = $this->getCourierCompanyApartment($courier['courier_id']);
         //$courier = D('OCourierCompanyOrganization')->getCompanyCourier($courier['courier_id']);
         if(empty($courier)) {
              $this->ret(3);
          }
        }
        else 
        {
            $courier = $this->getCourier($courier['courier_id']);
            if(empty($courier)) {
               $this->ret(3);
             
             }
        
        }

      if(empty(D('OCourierOrganization')->where([
          'courier_id' => $courier['courierId'],
          'organization_id' => $this->_apartmentId,
       ])->find())) {
           $this->ret(4);
       }

        if($courier) {
            $res['courier']      = $courier;
        } else {
            $this->ret(3);
        }
        $this->ret(0, $res);
    }
   
 	 //通过刷卡找MEMBER信息 （资产柜新加）
     /**
     * @apiDefine identifyCardForRent
     * @apiParam {String} accessToken
     * @apiParam {String} cardCode 租借人ID卡号码
     *
     * @apiSuccess {Number} ret
            '0' => 'identifyCard success',                                                     
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty card code',                           
            '3' => 'no matched member found',                                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.memberName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "member": {
     *             "memberId": "10001",
     *             "memberName": "RobbinSun"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function identifyCardForRent() {
        $cardCode = I('post.cardCode');
        if(empty($cardCode))
	    {
           $this->ret(2);
        }
		$wh['cardcode'] = $cardCode;
        $courier = D('Member')->getMember($wh);
         if($courier) {
			//$res['member']      = $courier;
            $res['member']['member_id']      = $courier['member_id'];
        } else {
            $this->ret(3);
        }
        $this->ret(0, $res);
    }   
	
     //通过刷卡找库存product信息 （资产柜新加）
     /**
     * @apiDefine identifyCardForReturn
     * @apiParam {String} accessToken
     * @apiParam {String} cardCode 产品ID卡号码
     * @apiParam {String} inputflag 标志位 0、status=3 1、status=1 2、not care status 3、status=0 or 3
     *
     * @apiSuccess {Number} ret
            '0' => 'identifyCard success',                                                     
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty card code',    
            '3' => 'empty inputflag',                         
            '4' => 'no matched product found',     
            '5' => 'no matched product found in product table',			
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.product
     * @apiSuccess {String}     data.product.productId
     * @apiSuccess {String}     data.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "product": {
     *             "productId": "10001",
     *             "productName": "Glass"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
     public function identifyCardForReturn() {
        $cardCode = I('post.cardCode');
        $inputflag = I('post.inputflag');
        if(empty($cardCode))
	    {
           $this->ret(2);
        }
        if(empty($cardCode))
	    {
           $this->ret(3);
        }
        if($inputflag=='0')
        {
		 $wh=[
		    'rfid' =>$cardCode,
			'product_status_code'=>'3',
            ];
			$courier = D('ProductInventory')->getMember($wh);
        }
        else if($inputflag=='1')
        {
            $wh=[
                'rfid' =>$cardCode,
                'product_status_code'=>'0',
                ]; 
				$courier = D('ProductInventory')->getMember($wh);
        }
        else if($inputflag=='2')
        {

            $wh=[
                'rfid' =>$cardCode,
                ]; 
				$courier = D('ProductInventory')->getMember($wh);
        }   
        else if($inputflag=='3')
        {
			$wh1=[
                'rfid' =>$cardCode,
				'product_status_code' => '0',
                ];
		    $wh2=[
                'rfid' =>$cardCode,
				'product_status_code' => '3',
                ];	
			$wh['_complex'] = array(
              $wh1,
              $wh2,
              '_logic' => 'or'
            );            				
		    $courier = D('ProductInventory')->getMember($wh);
        }  
        if($courier) {
			//$res['product']      = $courier;
			$res['product']['product_inventory_id'] = $courier['product_inventory_id'];
			$res['product']['product_id'] = $courier['product_id'];
			$res['product']['organization_id'] = $courier['organization_id'];
			$res['product']['member_id'] = $courier['member_id'];
			//$res['product']['boxmodel_id'] = $courier['boxmodel_id'];
			$res['product']['rfid'] = $courier['rfid'];
        } else {
            $this->ret(4);
        }
		 $wh=[
                'product_id' =>$courier['product_id'],
             ];
		$courier = D('Product')->getMember($wh);
		if($courier) {
			$res['product']['boxmodel_id'] = $courier['boxmodel_id'];
        } else {
            $this->ret(5);
        }
		
        $this->ret(0, $res);
    } 
	 //通过刷卡barcode找库存product信息 （资产柜新加）
     /**
     * @apiDefine identifyCardForReturnBarcode
     * @apiParam {String} accessToken
     * @apiParam {String} cardCode 产品ID卡号码
     * @apiParam {String} inputflag 标志位 0、status=3 1、status=1 2、not care status 3、status=0 or 3
     *
     * @apiSuccess {Number} ret
            '0' => 'identifyCard success',                                                     
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty card code',    
            '3' => 'empty inputflag',                         
            '4' => 'no matched product found',     
            '5' => 'no matched product found in product table',			
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.product
     * @apiSuccess {String}     data.product.productId
     * @apiSuccess {String}     data.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "product": {
     *             "productId": "10001",
     *             "productName": "Glass"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
     public function identifyCardForReturnBarcode() {
        $cardCode = I('post.cardCode');
        $inputflag = I('post.inputflag');
        if(empty($cardCode))
	    {
           $this->ret(2);
        }
        if(empty($cardCode))
	    {
           $this->ret(3);
        }
        if($inputflag=='0')
        {
		 $wh=[
		    'barcode' =>$cardCode,
			'product_status_code'=>'3',
            ];
			$courier = D('ProductInventory')->getMember($wh);
        }
        else if($inputflag=='1')
        {
            $wh=[
                'barcode' =>$cardCode,
                'product_status_code'=>'0',
                ]; 
				$courier = D('ProductInventory')->getMember($wh);
        }
        else if($inputflag=='2')
        {

            $wh=[
                'barcode' =>$cardCode,
                ]; 
				$courier = D('ProductInventory')->getMember($wh);
        }   
        else if($inputflag=='3')
        {
			$wh1=[
                'barcode' =>$cardCode,
				'product_status_code' => '0',
                ];
		    $wh2=[
                'barcode' =>$cardCode,
				'product_status_code' => '3',
                ];	
			$wh['_complex'] = array(
              $wh1,
              $wh2,
              '_logic' => 'or'
            );            				
		    $courier = D('ProductInventory')->getMember($wh);
        }  
        if($courier) {
			//$res['product']      = $courier;
			$res['product']['product_inventory_id'] = $courier['product_inventory_id'];
			$res['product']['product_id'] = $courier['product_id'];
			$res['product']['organization_id'] = $courier['organization_id'];
			$res['product']['member_id'] = $courier['member_id'];
			//$res['product']['boxmodel_id'] = $courier['boxmodel_id'];
			$res['product']['barcode'] = $courier['barcode'];
        } else {
            $this->ret(4);
        }
		 $wh=[
                'product_id' =>$courier['product_id'],
             ];
		$courier = D('Product')->getMember($wh);
		if($courier) {
			$res['product']['boxmodel_id'] = $courier['boxmodel_id'];
        } else {
            $this->ret(5);
        }
		
        $this->ret(0, $res);
    } 
	 /**得到库存产品列表（资产柜新加）
     * @apiDefine getProductInventoryList
     * @apiParam {String}   accessToken
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',                                                       
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.productId
     * @apiSuccess {String}     data.productList.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productList": [
     *             {
     *                 "productId": "10001",
     *                 "productName": "1"
     *             },
     *             {
     *                 "productId": "10002",
     *                 "productName": "2"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductInventoryList() {
  
        /*  
        $apartmentId = I('request.organizationId');

        if(empty($apartmentId)){
            $this->ret(2);
        }
		*/
		$wh=
		[
		  't.cabinet_id'=>$this->_cabinetId,
		  't.product_status_code' => '1',
		];
        $unitArr = D('ProductInventory')->getProductInventoryArr($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }
     /**得到库存产品列表（资产柜新加）
     * @apiDefine getProductInventoryListN
     * @apiParam {String}  accessToken
	 * @apiParam {String}  categoryId
     * @apiParam {String}  memberId       租借人Id	 
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',                                                       
            '3' => 'no product', 	
            '4' => 'no this member',			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.productId
     * @apiSuccess {String}     data.productList.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productList": [
     *             {
     *                 "productId": "10001",
     *                 "productName": "1"
     *             },
     *             {
     *                 "productId": "10002",
     *                 "productName": "2"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductInventoryListN() {
		$memberId     = I('request.memberId');
		if(empty($memberId))  { $this->ret(4);}
		//
		$wh=
		[
		  'cabinet_id'=>$this->_cabinetId,
		];
		$organization=D('OOrganizationCabinet')->getMember($wh);
		if(empty($organization))
		{
			$this->ret(5);
		}
		//
		$categoryId = I('request.categoryId');
		if(empty($categoryId))
		{

		 $wh=
		 [
           't.organization_id' => $organization['organization_id'],
		   
		 ];	
 		 		 
		}
	    else
        {
		 $wh=
		 [
           't.organization_id' => $organization['organization_id'],
		   'o.category_id' => $categoryId,
		 ];		

		}
		
		//
		// $product=D('Product')->getList($wh);
		// foreach($product as $k => $c) {
			// $wh=
		    // [
             // 'product_id' => $c['product_id'],
			 // 'product_status_code' => '1',
			 // 'cabinet_id' => $this->_cabinetId,
		    // ];	
			// $Arr = D('ProductInventory')->getList($wh);
			// foreach($Arr as $a => $b) {
				// $unitArr[$b['product_inventory_id']] = [
			    // 'product_inventory_id' => $b['product_inventory_id'],
			    // 'product_id' => $b['product_id'],
                // 'cabinet_id' => $b['cabinet_id'],
				// 'organization_id' => $b['organization_id'],
				// 'member_id' => $b['member_id'],
				// 'boxmodel_id' => $c['boxmodel_id'],
                // 'rfid' => $b['rfid'],
                // 'product_name' => $c['product_name'],
                // 'brand' => $c['brand'],
                // 'manufacturer' => $c['manufacturer'],
                // 'box_id' => $b['box_id'],
                // 'part_num' => $c['part_num'],
				// 'product_image' => $c['product_image'],
				// 'product_thumbnail' => $c['product_thumbnail'],
              // ];
			// }
		// }	
		/*
		$wh1=[
		        
		        't.product_status_code' => '1',
                ];
		$wh2=[
		      
		        't.product_status_code' => '3',
                ];	
		$wh3=[
		        
		        't.product_status_code' => '0',
				'o.orderable' => '1',
            ];	
	    $wh['_complex'] = array(
              $wh1,
              $wh2,
			  $wh3,
              '_logic' => 'or'
            ); 
      	*/	
		//
		 $wh=
		 [
           't.product_status_code' => '1',
		 ];	
	    $unitArr = D('ProductInventory')->getProductInventoryArrOrder($wh);	
		if(empty($unitArr)){
            $this->ret(3);
        }
		
        $data = [
            'productList' => array_values($unitArr),
        ];
        
        $this->ret(0, $data);
    }
	
	 /**租借预分配柜子（资产柜新加）
     * @apiDefine preAuthForBoxRent 
     * @apiParam {String} accessToken
     * @apiParam {String} boxModelId      选择箱体类型   
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForBox success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty box model id',                                                       
            '3' => 'fail to assign box',                   
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.box
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForRent
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.bodySequence
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
    public function preAuthForBoxRent() {

        $boxModelId = I('request.boxModelId');
		
        if(empty($boxModelId)) { $this->ret(2);}

        $boxId = D('CabinetBox')->assignBox($this->_cabinetId, $boxModelId, False);
        if(empty($boxId)) { $this->ret(3);}

        $box = D('CabinetBox')->getBodyBox($boxId);
		
        //$boxModel = D('CabinetBoxModel')->getByModelId($boxModelId);

        $this->ret(0, [
            'box' => [
                'boxId' => $boxId,
                'lockAddr' => $box['lock_addr'],
                'boxAddr' => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }	
	
	 /**根据BOXID得到柜隔口硬件信息（资产柜新加）
     * @apiDefine getBoxInfoForRent
     * @apiParam {String} accessToken
     * @apiParam {String} boxId      选择箱体类型   
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForBox success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty box id',                                                       
            '3' => 'fail ',                   
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.box
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForRent
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.bodySequence
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
    public function getBoxInfoForRent() {

        $boxId    = I('request.boxId');
        
        if(empty($boxId)) { $this->ret(2);}


        $box = D('CabinetBox')->getBodyBox($boxId);
	
	    if(empty($box)) { $this->ret(3);}
		
        $this->ret(0, [
            'box' => [
                'boxId' => $boxId,
                'lockAddr' => $box['lock_addr'],
                'boxAddr' => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }	

	 /**根据RFID得到库存boxmodelID（资产柜新加）
     * @apiDefine getBoxModelFromRfid
     * @apiParam {String} accessToken
     * @apiParam {String} rfId      选择箱体类型   
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty box id',                                                       
            '3' => 'no product',     
            '4' => 'fail ', 			
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.box
     * @apiSuccess {String}   data.box.boxmodelId     开箱boxID, 用于commitForRent
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Success",
     *     "data": {
     *         "box": {
     *             "boxmodelId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function getBoxModelFromRfid() {

        $rfId    = I('request.rfId');
        
        if(empty($rfId)) { $this->ret(2);}
		$wh=[
		'rfid'=>$rfId,
		];
		$box =D('ProductInventory')->getMember($wh);
	    if(empty($box)) { $this->ret(3);}
		//
		$wh=[
		'product_id'=>$box['product_id'],
		];
		$box =D('Product')->getMember($wh);
		if(empty($box)) { $this->ret(4);}
		//
        $this->ret(0, [
            'box' => [
                'boxmodelId' => $box['boxmodel_id'],
            ]
        ]);
    }	
	 /**释放BOXID（资产柜新加）
     * @apiDefine releaseBoxid
     * @apiParam {String} accessToken
     * @apiParam {String} boxId      选择箱体类型   
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForBox success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty box id',                                                       
            '3' => 'fail ',                   
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.box
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForRent
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.bodySequence
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
    public function releaseBoxid() {

        $boxId    = I('request.boxId');
        
        if(empty($boxId)) { $this->ret(2);}


        $box = D('CabinetBox')->releaseBox($boxId);
	
	    if(empty($box)) { $this->ret(3);}
		
        $this->ret(0, [
            'box' => [
                'boxId' => $boxId,
                'lockAddr' => $box['lock_addr'],
                'boxAddr' => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }	
     /**资产柜借提交
     * @apiDefine commitForAssetRentN 
     * @apiParam {String} accessToken
     * @apiParam {String} inventoryId       库存Id
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty organization id',                           
            '3' => 'empty inventory id',  
            '6' => 'empty box id',	
            '8' => 'update rental table fail',	
            '9' => 'update inventory table fail',	
            '10' => 'no the product',				
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.rent
     * @apiSuccess {String}   data.rent.rentalId
     * @apiSuccess {String}   data.rent.memberId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForAssetRent Success",
     *     "data": {
     *         "rent": {
     *             "rentalIdId": "10007",
     *             "memberId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForAssetRentN() 
	{

        $memberId     = I('request.inventoryId');
        if(empty($memberId))  { $this->ret(3);}
		$now = time();
		//
	    $wh=[
		'product_inventory_id'=>$memberId,
		];
		$productinfo =D('ProductInventory')->getMember($wh);
	    if(empty($productinfo)) { $this->ret(10);}
		//释放柜子
		D('CabinetBox')->releaseBox($productinfo['box_id']);

		//更新INVENTORY状态
        $store = [
		    'member_id'=>'0',
            'update_time' => $now,
            'product_status_code' => '0',
            'box_id'=>'0',
        ];
        $storeId = D('ProductInventory')->updateMember($wh,$store);
        if(empty( $storeId))       { $this->ret(9);}

        $this->ret(0, [
            'rent' => $rental,
        ]);
    }
	 /**资产柜借提交
     * @apiDefine commitForAssetRent 
     * @apiParam {String} accessToken
     * @apiParam {String} memberId       租借人Id
     * @apiParam {String} rfId           产品rfid
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty organization id',                           
            '3' => 'empty inventory id',  
            '4' => 'empty member id',                           
            '5' => 'empty rf id',	
            '6' => 'empty box id',	
            '8' => 'update rental table fail',	
            '9' => 'update inventory table fail',	
            '10' => 'no the product',				
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.rent
     * @apiSuccess {String}   data.rent.rentalId
     * @apiSuccess {String}   data.rent.memberId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForAssetRent Success",
     *     "data": {
     *         "rent": {
     *             "rentalIdId": "10007",
     *             "memberId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForAssetRent() 
	{
       // $apartmentId    = I('request.organizationId');
	   //	$inventoryId     = I('request.inventoryId');
        $memberId     = I('request.memberId');
        $rfId    = I('request.rfId');
		//$boxId    = I('request.boxId');
        //if(empty($apartmentId))   { $this->ret(2);}
		//if(empty($inventoryId))   { $this->ret(3);}
        if(empty($memberId))  { $this->ret(4);}
        if(empty($rfId ))  { $this->ret(5);}
		//
	    $wh=[
		'rfid'=>$rfId,
		'product_status_code'=>'1',
		];
		$productinfo =D('ProductInventory')->getMember($wh);
	    if(empty($productinfo)) { $this->ret(10);}
		
	    $wh1=[
		'product_id'=>$productinfo['product_id'],
		];
		$productname=D('Product')->getMember($wh1);
		//
        $now = time();
        //$holdTime = 86400;
        //$pickCode = \Org\Util\String::randString(6, 2);
        $rental = array(
            //'rental_id'
			'organization_id' => $productinfo['organization_id'],
            'cabinet_id' => $this->_cabinetId,
            'product_inventory_id' => $productinfo['product_inventory_id'],
			'box_id' => $productinfo['box_id'],
			'rfid' => $rfId,
            'member_id' => $memberId,
			//'pickup_code' =>$pickCode,
            //'expire_time' => $now + $holdTime,
            'rental_time' => $now,
			'rental_status_code'=>'3',
        );
        $boxId = D('ProductRental')->insertMember($rental);
        if(empty($boxId))       { $this->ret(6);}
		//释放柜子
		D('CabinetBox')->releaseBox($productinfo['box_id']);
        /*

		//更新rental状态
		$wh=['rfid'=>$rfId,'rental_status_code' => '0',];
		$rental = array(
            //'rental_id'	
            'member_id' => $memberId,
            'rental_time' => $now,
			'rental_status_code' => '3',
         );

        $boxId = D('ProductRental')->getMember($wh);
        if(empty($boxId))       { $this->ret(6);}
		*/
        
        /*
        $rentalId = D('ProductRental')->updateMember($wh,$rental);
        if(empty($rentalId))       { $this->ret(8);}
        */
		
		//发邮件
	    // async send notice
/*         S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ASSET_RENT'),
            'member_id' =>$memberId,
            'data' => [
                'cabinet_id' => $productinfo['cabinet_id'],
            ] 
        ]));	 */
		
        //SMS notice && Email notice
        //$Notice = new \Common\Common\Notice();
        //$Notice->notice(C('NOTICE.NT_ASSET_RENT'), $memberId, [
          // 'cabinet_id' => $this->_cabinetId,
		   
        //]);
		
        // $wh=['rfid'=>$rfId,];
		//更新INVENTORY状态
        $store = [
		    'member_id'=>$memberId,
            'update_time' => $now,
            'product_status_code' => '3',
            'box_id'=>'0',
        ];
        $storeId = D('ProductInventory')->updateMember($wh,$store);
        if(empty( $storeId))       { $this->ret(9);}

        $this->ret(0, [
            'rent' => $rental,
        ]);
    }

	 /**租借人归还预分配柜子（资产柜新加）
     * @apiDefine preAuthForBoxReturn 
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
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForStore
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.bodySequence
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
    public function preAuthForBoxReturn() {

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
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }

	 /**租借人归还提交（资产柜新加）
     * @apiDefine commitForAssetReturn 
     * @apiParam {String} accessToken
     * @apiParam {String} rfId           产品rfid
     * @apiParam {String} boxId          箱体号
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty rf id',  
            '3' => 'empty box id', 	
            '4' => 'fail occupyBox', 
            '5' => 'no this pruduct',
            '7' => 'fail uptate Inventory',	
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.rent
     * @apiSuccess {String}   data.rent.rentalId
     * @apiSuccess {String}   data.rent.memberId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForAssetRent Success",
     *     "data": {
     *         "rent": {
     *             "rentalIdId": "10007",
     *             "memberId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForAssetReturn() 
	{

        $rfId    = I('request.rfId');
		$boxId    = I('request.boxId');
        
        if(empty($rfId ))  { $this->ret(2);}
        if(empty($boxId ))  { $this->ret(3);}
  

        $now = time();
        $pickCode = \Org\Util\String::randString(6, 2);
        $holdTime = 86400;

        $rental = array(
            //'rental_id'	
			//'rfid' => $rfId,
            'return_time' => $now,
			'rental_status_code'=>'1',
			'return_locker_id'=>$this->_cabinetId,
        );

		$wh=[
		   'rfid'=> $rfId,
		   'rental_status_code'=>'3',
         ];
       
        $rentalIdold = D('ProductRental')->getMember($wh);
        //更新rental
        $rentalId = D('ProductRental')->updateMember($wh,$rental);
        //if(empty($rentalId ))  { $this->ret(5);}
        //发邮件通知
		if(empty($rentalId ))
		{}
	    else 
		{
		  //发邮件
	      // async send notice
        /*           S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ASSET_RETURN'),
            'member_id' =>$rentalId['member_id'],
            'data' => [
                'cabinet_id' => $this->_cabinetId,
             //   'pick_code' => $pickCode,
            ]
           ]));	 
		   */
		    //SMS notice && Email notice
            $Notice = new \Common\Common\Notice();
            $Notice->notice(C('NOTICE.NT_ASSET_RETURN'), $rentalIdold['member_id'], [
           'cabinet_id' => $this->_cabinetId,
		   
           ]);
		}
        //		
        //拥有BOXID
        if(empty(D('CabinetBox')->occupyBox($boxId))) {
            $this->ret(4);
        }



        //
		//$pickCode = \Org\Util\String::randString(6, 2);
			//更新INVENTORY状态
		//$wh=['rfid'=>$rfId,'product_status_code' => '3',];
		//$wh=['rfid'=>$rfId,];
		$wh1=[
                'rfid' =>$rfId,
				'product_status_code' => '0',
                ];
		$wh2=[
                'rfid' =>$rfId,
				'product_status_code' => '3',
                ];	
	    $wh['_complex'] = array(
              $wh1,
              $wh2,
              '_logic' => 'or'
            ); 
		$inventory = array(
            //'inventory_id'
            'box_id'=>$boxId,	
            'cabinet_id'=>$this->_cabinetId,			
			'member_id'=>'0',
            'update_time' => $now,
			'product_status_code' => '1',
         );
		$storeId = D('ProductInventory')->updateMember($wh,$inventory);
		if(empty($storeId))       
		{ 
		  D('CabinetBox')->releaseBox($boxId);
		  $this->ret(7);
		}
        //更新INVENTORY状态	
        /*
        //rental表插入新数据
        $wh=['rfid'=>$rfId,];
        $storeId =D('ProductInventory')->getMember($wh);
		if(empty($storeId))
        //
        { $this->ret(8);}
        //rental表插入新初始化数据
        $rental = array(
            //'rental_id'	
			'product_inventory_id' => $storeId['product_inventory_id'],
			'organization_id' => $storeId['organization_id'],
            'cabinet_id' => $this->_cabinetId,
			'rfid' => $rfId,
            'box_id' => $boxId ,
			'pickup_code' =>$pickCode,
			'rental_status_code' =>'0',
         );
         $rentalId = D('ProductRental')->insertMember($rental);
		 if(empty($rentalId))       { $this->ret(9);}
		*/
        //
        $store = [
            'box_id'=>$boxId,
            'return_time' => $now,
			'rental_status_code'=>'1',
			'return_locker_id'=>$this->_cabinetId,
        ];

        $this->ret(0, [
            'return' => $store,
        ]);
    }
	 /**租借人归还提交（资产柜新加）
     * @apiDefine commitForAssetReturnBarcode 
     * @apiParam {String} accessToken
     * @apiParam {String} rfId           产品barcode
     * @apiParam {String} boxId          箱体号
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty rf id',  
            '3' => 'empty box id', 	
            '4' => 'fail occupyBox', 
            '5' => 'no this pruduct',
            '7' => 'fail uptate Inventory',	
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.rent
     * @apiSuccess {String}   data.rent.rentalId
     * @apiSuccess {String}   data.rent.memberId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForAssetRent Success",
     *     "data": {
     *         "rent": {
     *             "rentalIdId": "10007",
     *             "memberId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForAssetReturnBarcode() 
	{

        $rfId    = I('request.rfId');
		$boxId    = I('request.boxId');
        
        if(empty($rfId ))  { $this->ret(2);}
        if(empty($boxId ))  { $this->ret(3);}
  

        $now = time();
        $pickCode = \Org\Util\String::randString(6, 2);
        $holdTime = 86400;

        $rental = array(
            //'rental_id'	
			//'rfid' => $rfId,
            'return_time' => $now,
			'rental_status_code'=>'1',
			'return_locker_id'=>$this->_cabinetId,
        );

		$wh=[
		   'rfid'=> $rfId,
		   'rental_status_code'=>'3',
         ];
       
        $rentalIdold = D('ProductRental')->getMember($wh);
        //更新rental
        $rentalId = D('ProductRental')->updateMember($wh,$rental);
        //if(empty($rentalId ))  { $this->ret(5);}
        //发邮件通知
		if(empty($rentalId ))
		{}
	    else 
		{
		  //发邮件
	      // async send notice
        /*           S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ASSET_RETURN'),
            'member_id' =>$rentalId['member_id'],
            'data' => [
                'cabinet_id' => $this->_cabinetId,
             //   'pick_code' => $pickCode,
            ]
           ]));	 
		   */
		    //SMS notice && Email notice
            $Notice = new \Common\Common\Notice();
            $Notice->notice(C('NOTICE.NT_ASSET_RETURN'), $rentalIdold['member_id'], [
           'cabinet_id' => $this->_cabinetId,
		   
           ]);
		}
        //		
        //拥有BOXID
        if(empty(D('CabinetBox')->occupyBox($boxId))) {
            $this->ret(4);
        }



        //
		//$pickCode = \Org\Util\String::randString(6, 2);
			//更新INVENTORY状态
		//$wh=['rfid'=>$rfId,'product_status_code' => '3',];
		//$wh=['rfid'=>$rfId,];
		$wh1=[
                'barcode' =>$rfId,
				'product_status_code' => '0',
                ];
		$wh2=[
                'barcode' =>$rfId,
				'product_status_code' => '3',
                ];	
	    $wh['_complex'] = array(
              $wh1,
              $wh2,
              '_logic' => 'or'
            ); 
		$inventory = array(
            //'inventory_id'
            'box_id'=>$boxId,	
            'cabinet_id'=>$this->_cabinetId,			
			'member_id'=>'0',
            'update_time' => $now,
			'product_status_code' => '1',
         );
		$storeId = D('ProductInventory')->updateMember($wh,$inventory);
		if(empty($storeId))       
		{ 
		  D('CabinetBox')->releaseBox($boxId);
		  $this->ret(7);
		}
        //更新INVENTORY状态	
        /*
        //rental表插入新数据
        $wh=['rfid'=>$rfId,];
        $storeId =D('ProductInventory')->getMember($wh);
		if(empty($storeId))
        //
        { $this->ret(8);}
        //rental表插入新初始化数据
        $rental = array(
            //'rental_id'	
			'product_inventory_id' => $storeId['product_inventory_id'],
			'organization_id' => $storeId['organization_id'],
            'cabinet_id' => $this->_cabinetId,
			'rfid' => $rfId,
            'box_id' => $boxId ,
			'pickup_code' =>$pickCode,
			'rental_status_code' =>'0',
         );
         $rentalId = D('ProductRental')->insertMember($rental);
		 if(empty($rentalId))       { $this->ret(9);}
		*/
        //
        $store = [
            'box_id'=>$boxId,
            'return_time' => $now,
			'rental_status_code'=>'1',
			'return_locker_id'=>$this->_cabinetId,
        ];

        $this->ret(0, [
            'return' => $store,
        ]);
    }
	 /**管理员归还预分配柜子（资产柜新加）
     * @apiDefine preAuthForAdmin 
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
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForStore
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.bodySequence
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
    public function preAuthForAdmin() {

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
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }

	 /**得到产品列表（资产柜新加）
     * @apiDefine getProductList
     * @apiParam {String}   accessToken
     * @apiParam {String}   organizationId 公寓ID，这里是所有者

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'invalid organizationId', 
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.productId
     * @apiSuccess {String}     data.productList.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productList": [
     *             {
     *                 "productId": "10001",
     *                 "productName": "1"
     *             },
     *             {
     *                 "productId": "10002",
     *                 "productName": "2"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductList() {

        $apartmentId = I('request.organizationId');

        if(empty($apartmentId)){
            $this->ret(2);
        }
		$wh['organization_id'] = $apartmentId;
        $unitArr = D('Product')->getProductArr($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }
	//
		 /**得到产品列表（资产柜新加）
     * @apiDefine getProductListN
     * @apiParam {String}   accessToken
     * @apiParam {String}   categoryId
     * @apiParam {String}   rentmemberId 
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',  
            '2' => 'invalid organization id', 			
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.product_id
     * @apiSuccess {String}     data.productList.product.organization_id
	 * @apiSuccess {String}     data.productList.product.product_name
     * @apiSuccess {String}     data.productList.product.category_id
	 * @apiSuccess {String}     data.productList.product.boxmodel_id
     * @apiSuccess {String}     data.productList.product.brand
	 * @apiSuccess {String}     data.productList.product.manufacturer
     * @apiSuccess {String}     data.productList.product.uom
	 * @apiSuccess {String}     data.productList.product.part_num
     * @apiSuccess {String}     data.productList.product.model_num
	 * @apiSuccess {String}     data.productList.product.is_public
     * @apiSuccess {String}     data.productList.product.product_desc
	 * @apiSuccess {String}     data.productList.product.product_image
     * @apiSuccess {String}     data.productList.product.product_thumbnail
	 * @apiSuccess {String}     data.productList.product.create_time
     * @apiSuccess {String}     data.productList.product.update_time
	 * @apiSuccess {String}     data.productList.product.end_date
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productList": [
     *             {
     *              "product_id": "203",
     *              "organization_id": "10002",
     *              "product_name": "samsung camera2345",
     *              "category_id": "1",
     *              "boxmodel_id": "10001",
     *              "brand": "",
     *              "manufacturer": "31313",
     *              "uom": "",
     *              "part_num": null,
     *              "model_num": null,
     *              "is_public": null,
     *              "product_desc": "213123",
     *              "product_image": "uploads/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *              "product_thumbnail": "uploads/thumb/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *              "instruction": "",
     *              "create_time": "1563069062",
     *              "update_time": "1563165281",
     *              "end_date": null,
	 *              "available": 1
     *             },
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductListN() {
  
        $rentmemberId = I('request.rentmemberId');
		$categoryId = I('request.categoryId');
		//
		$wh=
		[
		  'cabinet_id'=>$this->_cabinetId,
		];
		$organization=D('OOrganizationCabinet')->getMember($wh);
	    if(empty($organization))
		{
			$this->ret(1);
		}
		//
		if(empty($categoryId))
		{
		  $wh['organization_id'] = $organization['organization_id'];
		}
		else
		{
	      $wh=
		  [
           'organization_id' => $organization['organization_id'],
		   'category_id' => $categoryId,
		  ];	
		}	
		
         $unitArr = D('Product')->getProductArrN1($wh,$rentmemberId);
         //$unitArr = D('Product')->getProductArrN1($wh,'33935');
		if(empty($unitArr)){
            $this->ret(2);
        }
        $data = [
            'productList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
        //$this->ret(0, $rentmemberId);
    }
	 /**得到产品类别列表（资产柜新加）
     * @apiDefine getCategoryList
     * @apiParam {String}   accessToken
     * @apiParam {String}   organizationId 公寓ID，这里是所有者

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'invalid organizationId', 
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.productId
     * @apiSuccess {String}     data.productList.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productCateList": [
     *             {
     *                 "productId": "10001",
     *                 "productName": "1"
     *             },
     *             {
     *                 "productId": "10002",
     *                 "productName": "2"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getCategoryList() {

        $apartmentId = I('request.organizationId');

        if(empty($apartmentId)){
            $this->ret(2);
        }
		$wh['organization_id'] = $apartmentId;
        $unitArr = D('ProductCategory')->getProductCategoryArrN($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productCateList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }	 
    /**得到产品类别列表（资产柜新加）
     * @apiDefine getCategoryListN
     * @apiParam {String}   accessToken
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'invalid organizationId', 
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.productId
     * @apiSuccess {String}     data.productList.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productCateList": [
     *             {
     *                 "productId": "10001",
     *                 "productName": "1"
     *             },
     *             {
     *                 "productId": "10002",
     *                 "productName": "2"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getCategoryListN() {

        $wh=$this->_cabinetId;
        $unitArr = D('OOrganizationCabinet')->getApartmentCabinet($wh);
		if(empty($unitArr)){
            $this->ret(2);
        }
	    $wh=
		[
		      'organization_id'=>$unitArr['organization_id'],
		];

        $unitArr = D('ProductCategory')->getProductCategoryArrN($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productCateList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }	
	/**得到organizationid（资产柜新加）
     * @apiDefine getApartmentId
     * @apiParam {String}   accessToken
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'invalid organizationId', 
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.productId
     * @apiSuccess {String}     data.productList.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "organization": [
     *             {
     *                 "organization_id": "10001",
     *             },
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getApartmentId() {
        $wh=$this->_cabinetId;
        $unitArr = D('OOrganizationCabinet')->getApartmentCabinet($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $this->ret(0, [
            'organization' => [
                'organization_id' => $unitArr['organization_id'],
            ]
        ]);
    }
	
	 /**产品详细信息（资产柜新加）
     * @apiDefine getProductInfo 
     * @apiParam {String} accessToken
     * @apiParam {String} productId      产品ID
	 * @apiParam {String} categoryId      产品ID
	 * @apiParam {String}   organizationId 公寓ID，这里是所有者
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForBox success',
            '1' => 'invalid accesstoken',  			
            '2' => 'empty product id',                                      
            '3' => 'empty category id', 
            '4' => 'empty category id',			
            '5' => 'product not exist',
                   
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}     data.product
     * @apiSuccess {String}     data.product.productId
     * @apiSuccess {String}     data.product.productName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "PreAuthForStore Success",
     *     "data": {
     *         "product": {
     *                 "productId": "10001",
     *                 "productName": "1"
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function getProductInfo() {

        $productId    = I('request.productId');
        $categoryId    = I('request.categoryId');
        $apartmentId = I('request.organizationId');
		
        if(empty($productId)) { $this->ret(2);}
        if(empty($categoryId)) { $this->ret(3);}
		if(empty($apartmentId)){
            $this->ret(4);
        }
        $wh=[
		'product_id'=>$productId,
		'category_id'=>$categoryId,
		'organization_id'=>$apartmentId,
		];
        $ProductInfo = D('Product')->getMember($wh);
        if(empty($ProductInfo)) { $this->ret(5);}

        $this->ret(0, $ProductInfo);
    }

    /**管理员提交产品订单（资产柜新加）
     * @apiDefine commitForAssetAdmin
     * @apiParam {String} accessToken
     * @apiParam {String} userId         操作员Id
     * @apiParam {String} rfId           产品rfid
     * @apiParam {String} boxId          boxId
     * @apiParam {String} status_code          状态码
     * @apiParam {String} service_type         服务类型    
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                                                         
            '4' => 'empty manager id',   
            '5' => 'empty rfid',                                      
            '6' => 'empty box id',                                       
            '8' => 'empty status_code',  
            '9' => 'empty product_service_type', 			
            '10' => 'no  inventory data',   
            '11' => 'fail update',	
            '12' => 'fail insert rental table',		
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.inventory
     * @apiSuccess {String}   data.inventory.product_id
     * @apiSuccess {String}   data.inventory.organization_id
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForAssetRent Success",
     *     "data": {
     *         "inventory": {
     *             "product_id": "10007",
     *             "organization_id": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForAssetAdmin() 
	{
        //$productId    = I('request.productId');
		//$apartmentId    = I('request.organizationId');	
		$rfId    = I('request.rfId');
        $boxId         = I('request.boxId');
		//$boxmodelId    = I('request.boxmodelId');
        $userId     = I('request.userId');
		$status_code     = I('request.status_code');
		$service_type   = I('request.service_type');
        //if(empty($productId ))   { $this->ret(2);}
        //if(empty($apartmentId))  { $this->ret(3);}
        //if(empty($userId))   { $this->ret(4);}
		if(empty($rfId))   { $this->ret(5);}
        if(empty($boxId))  { $this->ret(6);}
		//if(empty($boxmodelId ))  { $this->ret(7);}
		if(empty($status_code))   { $this->ret(8);}
        if(empty($service_type))  { $this->ret(9);}

        $now = time();

        //$pickCode = \Org\Util\String::randString(6, 2);

        $holdTime = 86400;
		//


		
		$wh=[
        'rfid'=>$rfId,
        'product_status_code'=>0,
		];

        $storeId =D('ProductInventory')->getMember($wh);
		if(empty($storeId))
		{
           $this->ret(10);
        }
		/*
        //
        $rental = array(
            //'rental_id'	
			'product_inventory_id' => $storeId['product_inventory_id'],
			'organization_id' => $storeId['organization_id'],
            'cabinet_id' => $this->_cabinetId,
			'rfid' => $rfId,
            'box_id' => $boxId ,
			'pickup_code' =>$pickCode,
			'rental_status_code' =>'0',
         );
         $rentalId = D('ProductRental')->insertMember($rental);
		 if(empty($rentalId))       { $this->ret(12);}
        */
        //
		$inventory = array(
            //'inventory_id'	
			//'product_id' => $productId,
			//'organization_id' => $apartmentId,
            'cabinet_id' => $this->_cabinetId,
            'member_id' => '0',
			'rfid' => $rfId,
            'box_id' => $boxId ,
			'cabinet_id' =>$this->_cabinetId,
			//'pickup_code' =>$pickCode,
            'update_time' => $now,
			'product_status_code' => $status_code,
			'product_service_type' => $service_type,
         );
		$storeId = D('ProductInventory')->updateMember($wh,$inventory);
		if(empty($storeId))       { $this->ret(11);}
	    //占有柜子
		D('CabinetBox')->occupyBox($boxId);
		//
        $this->ret(0, [
            'inventory' => $inventory,
        ]);
    }
	 /**预取件
     * @apiDefine commitForAsset
     * @apiParam {String} accessToken
     * @apiParam {String} storeId
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForPick success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty store id',                           
            '3' => 'no matched store order found by this storeId',                           
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForPick Success",
     * }
     * @sendSampleRequest
     */
    public function commitForAsset() {

        $storeId = I('request.storeId');

        if(empty($storeId))       
		{ 
	       $this->ret(2);
		}

        $storeArr = $this->getStoreArrAsset(null, null, $storeId);

        if(empty($storeArr)) {
            $this->ret(3);
        }

        $store = array_shift($storeArr);
        $now = time();
            /*

            //send email to member

            // async send notice
            S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
                'notice_tpl' => C('NOTICE.NT_ZIPPORA_OVERDUE'),
                'member_id' => $store['toMemberId'],
                'data' => [
                    'cabinet_id' => $this->_cabinetId,
                    'pick_code' => $store['pickCode'],
                ]
            ]));
             */
            // update o_store pick_time, pick_with
		    $wh=
				[
				   'rental_id'=>$store['storeId'],
				];
				
            $rentalinfo=D('ProductRental')->updateMember($wh, [
                    'rental_time' => $now,
					'rental_status_code' => '3',
                    //'pick_with' => 'app',
                    //'pick_fee' => $ret['amount'],
                ]); 

           $rentalinfo=D('ProductRental')->getMember($wh);
					//释放柜子
		   D('CabinetBox')->releaseBox($rentalinfo['box_id']);	
				$wh=
				[
				   'product_inventory_id'=>$rentalinfo['product_inventory_id'],
				];
				
			D('ProductInventory')->updateMember($wh, [
			        'box_id'=>'0',
                    'update_time' => $now,
					'product_status_code' => '3',
                ]); 
      
            //update box status
            D('CabinetBox')->releaseBox($store['boxId']);

      

        $this->ret(0);
    }
	 /**预取件
     * @apiDefine proveCodeForAsset
     * @apiParam {String} accessToken
     * @apiParam {String} codeType  eg. pick
     * @apiParam {String} code
     *
     * @apiSuccess {Number} ret
            '0' => 'prove code success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty code or wrong codeType',                                      
            '3' => 'no matched pick orders',                                      
            '4' => 'wallet not enough, please recharge and try again.',                  
            '5' => 'you need to bind a credit card to your account',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.picks
     * @apiSuccess {Object}     data.picks.pick
     * @apiSuccess {String}     data.picks.pick.lockAddr
     * @apiSuccess {String}     data.picks.pick.boxAddr
     * @apiSuccess {String}     data.picks.pick.isAllocable
     * @apiSuccess {String}     data.picks.pick.storeId        订单ID，用于commitForPick
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "proveCode success",
          "data": {
            "picks": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "isAllocable" : "1",
                "storeId" : "10200"
              }
            ]
          }
      }
     *
     *
     * @sendSampleRequest
     */
    public function proveCodeForAsset() {

        $code = I('post.code', '', 'trim');
        $codeType = I('post.codeType', '', 'trim');

        if (empty($code) || empty($codeType) || !in_array($codeType, [
            'pick',
        ])) { $this->ret(2);}
        //格式化数据
        switch($codeType) {
            case 'pick':
                $storeArr       = $this->getStoreArrAsset(null, $code);
                if(empty($storeArr)) {
                    $this->ret(3);
                }
                break;
            default:
        }
        //$this->ret(0, $storeArr);
		
        //检查钱包
		
        /*
         if(!D('Wallet')->checkWallet($storeArr[0]['toMemberId'])) 
		{

                $money = D('Wallet')->getWallet($storeArr[0]['toMemberId']);
                $res['picks'][] = [
                           'lockAddr' => '0',
                           'boxAddr' => '0',
                           'isAllocable' => '0',
                           'storeId' => '0',
                           'money'  => $money['money'],
                     ];
                $this->ret(4,$res);
        }  */

        //$cFlag = I('post.cFlag') ? True : False;
        //$cFlag =true;
        $now = time();
        
        // update box.status
        foreach($storeArr as $store)
		{

            if($cFlag) {

                //charge store
                $box = D('CabinetBox')->getBox($store['boxId']);
                $apartment = D('OOrganization')->getApartment($this->_apartmentId);
                /*
                $ret = D('OCharge')->charge(
                    $store['toMemberId'],
                    $apartment['organization_id'],
                    $apartment['charge_rule'],
                    'box_penalty', [
                        'storeId' => $store['storeId'],
                        'boxModelId' => $box['box_model_id'],
                        'storeTime' => $store['storeTime'],
                    ]
                ); 
                */ 
                // update o_store pick_time, pick_with
				$wh=
				[
				   'rental_id'=>$store['storeId'],
				];
				
                $rentalinfo=D('ProductRental')->updateMember($wh, [
                    'rental_time' => $now,
					'rental_status_code' => '3',
                    //'pick_with' => 'app',
                    //'pick_fee' => $ret['amount'],
                ]); 

                $rentalinfo=D('ProductRental')->getMember($wh);
				
				$wh=
				[
				   'product_inventory_id'=>$rentalinfo['product_inventory_id'],
				];
				
				D('ProductInventory')->updateMember($wh, [
                    'update_time' => $now,
					'product_status_code' => '3',
                ]); 
				
                D('CabinetBox')->releaseBox($store['boxId']);
           }

            $res['picks'][] = [
                'lockAddr' => $store['lockAddr'],
                'boxAddr' => $store['boxAddr'],
                'isAllocable' => $store['isAllocable'],
                'storeId' => $store['storeId'],
                'money'  => $money['money'],
            ];
        }
		

        $this->ret(0, $res);
    }

     /**
     * @apiDefine identifyPackNo
     * @apiParam {String} accessToken
     * @apiParam {String} packCode 共享柜存包码
     *
     * @apiSuccess {Number} ret
            '0' => 'identifyPackNo success',                                                     
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty pack code',                           
            '3' => 'no packNo found  or package already store',                  
            '4' => 'you have not been authorized by this locker',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.package
     * @apiSuccess {String}     data.package.memberStoreId
     * @apiSuccess {String}     data.package.fromMember
     * @apiSuccess {String}     data.package.toMember
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "package": {
     *             "memberStoreId": "1",
     *             "fromMember": "21125",
     *             "toMember": "21137"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function identifyPackNo() {

        $packCode = I('post.packCode');
        if(empty($packCode)) {
        $this->ret(2);
        }
        
        //通过存包码查询是否有此存包码
        $map['store_id'] = array('EXP','is NULL');
        $map['access_code'] = $packCode;
        $memberpack = D('MemberStore')->getBypackCode($map);
        if(empty($memberpack)) 
        {
            $this->ret(3);
        }
        else 
        {
            //通过存包码与柜号确认是否在此柜中存放包裹 
            $map1['zipcode'] = $memberpack['zipcode'];
            $cabinets = D('Cabinet')->getCabinetsByZipcode($map1);
            foreach ($cabinets as $value) {
              if($this->_cabinetId == $value['cabinet_id'])	{
                	$package['memberStoreId'] = $memberpack['id'];
                	$map2['username'] = $memberpack['from_member'];
                	$frommem = D('MemberProfile')->getMember($map2);
                	$package['fromMember']    = $frommem['member_id'];
                	$package['fromMemberName']    = $frommem['first_name'].' '.$frommem['last_name'];
                	$map3['username'] = $memberpack['to_member'];
                	$tomem = D('MemberProfile')->getMember($map3);
             	    $package['toMember']  = $tomem['member_id'];
             	    $package['toMemberName']    = $tomem['first_name'].' '.$tomem['last_name'];
              }
            }

        
        }

        if($package) {
            $data['package']  = $package;
        } else {
            $this->ret(4);
        }


        $this->ret(0, $data);
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
     * @apiSuccess {String}     data.member.memberName
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getAppScanResult success",
          "data": {
            "member": {
              "memberId": "10009",
              "memberName": "ganiks liu",
            }
          }
     *
     * @sendSampleRequest
     */

    public function getAppScanResult() {
        parent::getAppScanResult();
    }

    /**
     * @apiDefine getPickList
     * @apiParam {String} accessToken
     * @apiParam {String} [memberId]
     * @apiParam {String} [passedDays]   超过的天数，如果设置为大于0的整数，则memberId参数无效，直接返回本快递柜中所有订单(并附storeTime)
     *
     * @apiSuccess {Number} ret
            '0' => 'getPickList success',                                                
            '1' => 'invalid accesstoken',                                                     
            '2' => 'empty memberId',                                                           
            '3' => 'no matched orders to pick up',                  
            '4' => 'wallet not enough, please recharge and try again.',                  
            '5' => 'you need to bind a credit card to your account',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.pickList
     * @apiSuccess {Object}     data.pickList.pick
     * @apiSuccess {String}     data.pickList.pick.lockAddr
     * @apiSuccess {String}     data.pickList.pick.boxAddr
     * @apiSuccess {String}     data.pickList.pick.bodySequence
     * @apiSuccess {String}     data.pickList.pick.isAllocable
     * @apiSuccess {String}     data.pickList.pick.storeId        订单ID，用于commitForPick
     * @apiSuccess {String}     data.pickList.pick.storeTime
     * @apiSuccess {String}     data.pickList.pick.memberName
     * @apiSuccess {String}     data.pickList.pick.unitName
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getPickList success",
          "data": {
            "pickList": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "bodySequence" : "01",
                "isAllocable" : "1",
                "storeId" : "10200"
              }
            ]
          }
     *
     * @sendSampleRequest
     */

    // public function getPickList() {

        // $memberId = I('post.memberId');
        // $passedDays = I('post.passedDays');

        // if($passedDays) {

            // //$passedDays++;
            // //$storeArr       = $this->getStoreArr(null, null, null, null, null, [
            // //    'store_time' => ['lt', strtotime(-$passedDays.' day')],//超过3天的订单
            // //]);
            // $storeArr       = $this->getStoreArr(null, null, null, null, null, []);

            // if(empty($storeArr)) {
                // $this->ret(3);
            // }

            // foreach($storeArr as $store) {

                // $toMember = D('OMemberOrganization')->getMember($store['toMemberId'], $this->_cabinetId);
                // $res['pickList'][] = [
                    // 'lockAddr' => $store['lockAddr'],
                    // 'boxAddr' => $store['boxAddr'],
                    // 'bodySequence' => $store['bodySequence'],
                    // 'isAllocable' => $store['isAllocable'],
                    // 'storeId' => $store['storeId'],
                    // 'storeTime' => $store['storeTime'],
                    // 'memberName' => $toMember['first_name'].' '.$toMember['last_name'],
                    // 'unitName' => $toMember['unit_name'],
                // ];
            // }
        // } else {

            // if (!$memberId) { $this->ret(2);}

            // $storeArr       = $this->getStoreArr($memberId);

            // if(empty($storeArr)) {
                // $this->ret(3);
            // }

            // if(!D('Wallet')->checkWallet($memberId)) {
               // // if(!D('CardCredit')->checkCard($memberId)) {
              // //      $this->ret(5);
              // //  } else {
                    // $this->ret(4);
             // //   }
            // }


            // $cFlag = I('post.cFlag') ? True : False;

            // // update box.status
            // foreach($storeArr as $store) {

                // if($cFlag) {

                    // $now = time();

                    // //charge store
                    // $box = D('CabinetBox')->getBox($store['boxId']);
                    // $apartment = D('OOrganization')->getApartment($this->_apartmentId);
                    // $ret = D('OCharge')->charge(
                        // $store['toMemberId'],
                        // $apartment['organization_id'],
                        // $apartment['charge_rule'],
                        // 'box_penalty', [
                            // 'storeId' => $store['storeId'],
                            // 'boxModelId' => $box['box_model_id'],
                            // 'storeTime' => $store['storeTime'],
                        // ]
                    // );

                    // // update o_store pick_time, pick_with
                    // D('OStore')->updateStore($store['storeId'], [
                        // 'pick_time' => $now,
                        // 'pick_with' => 'app',
                        // 'pick_fee' => $ret['amount'],
                    // ]);

                    // D('CabinetBox')->releaseBox($store['boxId']);
                // }

                // $res['pickList'][] = [
                    // 'lockAddr' => $store['lockAddr'],
                    // 'boxAddr' => $store['boxAddr'],
                    // 'bodySequence' => $store['bodySequence'],
                    // 'isAllocable' => $store['isAllocable'],
                    // 'storeId' => $store['storeId'],
                // ];
            // }
        // }

        // $this->ret(0, $res);
    // }
        public function getPickList() {

        $memberId = I('post.memberId');
        $passedDays = I('post.passedDays');
		$storeList = array();
	    $wh1=
		    [
		      'product_status_code' => '1',
		    ];
		$wh2=[
		      'product_status_code' => '2',
		    ];
	    $wh['_complex'] = array(
              $wh1,
              $wh2,
              '_logic' => 'or'
            ); 
		
		$storeList=D('ProductInventory')->getListArr($wh);
        foreach($storeList as $sto) {
			//
            $box = D('CabinetBox')->getBodyBox($sto['box_id']);
			//$wh3=$sto['product_id'];
			$wh3=[
		      'product_id' => $sto['product_id'],
		    ];
			$product= D('Product')->getMember($wh3);
            $res['pickList'][] = [
			    'productname' =>$product['product_name'],
				'rfid' => $sto['rfid'],
                'product_inventory_id'=> $sto['product_inventory_id'],
                'product_id'=> $sto['product_id'],
                'boxId'       => $box['box_id'],
                'cabinetId'   => $box['cabinet_id'],
                'lockAddr'    => $box['lock_addr'],
                'boxAddr'     => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ];
        }

      $this->ret(0, $res);
    }
    /**
     * @apiDefine getPickListPickMart
     * @apiParam {String} accessToken
     * @apiParam {String} [passedDays]   直接返回本快递柜中所有订单(并附storeTime)
     *
     * @apiSuccess {Number} ret
            '0' => 'getPickList success',                                                
            '1' => 'invalid accesstoken',                                                                                                               
            '2' => 'no matched orders to pick up',                  
                 
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.pickList
     * @apiSuccess {Object}     data.pickList.pick
     * @apiSuccess {String}     data.pickList.pick.lockAddr
     * @apiSuccess {String}     data.pickList.pick.boxAddr
     * @apiSuccess {String}     data.pickList.pick.bodySequence
     * @apiSuccess {String}     data.pickList.pick.isAllocable
     * @apiSuccess {String}     data.pickList.pick.storeId        订单ID，用于commitForPick
     * @apiSuccess {String}     data.pickList.pick.storeTime
     * @apiSuccess {String}     data.pickList.pick.memberName
     * @apiSuccess {String}     data.pickList.pick.unitName
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "getPickList success",
          "data": {
            "pickList": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "bodySequence" : "01",
                "isAllocable" : "1",
                "storeId" : "10200"
              }
            ]
          }
     *
     * @sendSampleRequest
     */

    public function getPickListPickMart() {

        $passedDays = I('post.passedDays');

        if($passedDays) {

            //$passedDays++;
            //$storeArr       = $this->getStoreArr(null, null, null, null, null, [
            //    'store_time' => ['lt', strtotime(-$passedDays.' day')],//超过3天的订单
            //]);
            $storeArr       = $this->getStoreArrPickMart(null, null, null, null, null, []);

            if(empty($storeArr)) {
                $this->ret(2);
            }

            foreach($storeArr as $store) {

                $toMember = D('OMemberOrganization')->getMember($store['toMemberId'], $this->_cabinetId);
                $res['pickList'][] = [
                    'lockAddr' => $store['lockAddr'],
                    'boxAddr' => $store['boxAddr'],
                    'bodySequence' => $store['bodySequence'],
                    'isAllocable' => $store['isAllocable'],
                    'storeId' => $store['storeId'],
                    'storeTime' => $store['storeTime'],
                    'memberName' => $toMember['first_name'].' '.$toMember['last_name'],
                    'unitName' => $toMember['unit_name'],
                ];
            }
        } 

        $this->ret(0, $res);
    }

    /**
     * @apiDefine commitForPick
     * @apiParam {String} accessToken
     * @apiParam {String} storeId   订单Id
     * @apiParam {String} [flag] 如果设置为1，则认为是特殊方式取件(管理员操作)，并不对用户进行扣款等操作。
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForPick success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty store id',                           
            '3' => 'no matched store order found by this storeId',                           
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForPick Success",
     * }
     * @sendSampleRequest
     */
    public function commitForPick() {

        $storeId         = I('request.storeId');

        if(empty($storeId))       { $this->ret(2);}

        $storeArr       = $this->getStoreArr(null, null, $storeId);
        if(empty($storeArr)) {
            $this->ret(3);
        }

        $store = array_shift($storeArr);
        $now = time();

        if(I('request.flag')) {
            //send email to member

            // async send notice
            S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
                'notice_tpl' => C('NOTICE.NT_ZIPPORA_OVERDUE'),
                'member_id' => $store['toMemberId'],
                'data' => [
                    'cabinet_id' => $this->_cabinetId,
                    'pick_code' => $store['pickCode'],
                ]
            ]));

            // update o_store pick_time, pick_with
            D('OStore')->updateStore($store['storeId'], [
                'pick_time' => $now,
                'pick_with' => 'locker',
            ]);
      
            //update box status
            D('CabinetBox')->releaseBox($store['boxId']);

        } else {
            //charge store
            $box = D('CabinetBox')->getBox($store['boxId']);
            $apartment = D('OOrganization')->getApartment($this->_apartmentId);
     
            /**
            // stop whole charge in pick modify everday crontab charge overdue package
            $ret = D('OCharge')->charge(
                $store['toMemberId'],
                $apartment['organization_id'],
                $apartment['charge_rule'],
                'box_penalty', [
                    'storeId' => $store['storeId'],
                    'boxModelId' => $box['box_model_id'],
                    'storeTime' => $store['storeTime'],
                ]
            );
            **/
            

           //$this->ret(0,[$data => $ret]); 
            
            //wallet money is negative can't pick package and notify user to charge wallet
            if(!D('Wallet')->checkWallet($store['toMemberId'])) {
                 $money = D('Wallet')->getWallet($store['toMemberId']);
                 $this->ret(4,$money);
            }

            $ret['amount'] = 0;
            
            
            // update o_store pick_time, pick_with
            D('OStore')->updateStore($store['storeId'], [
                'pick_time' => $now,
                'pick_with' => 'app',
                'pick_fee' => $ret['amount'],
            ]);

            //update box status
            D('CabinetBox')->releaseBox($store['boxId']);
        }

        $this->ret(0);
    }
    
/**
     * @apiDefine commitForPickMart
     * @apiParam {String} accessToken
     * @apiParam {String} storeId   订单Id
     * @apiParam {String} [flag] 如果设置为1，则认为是特殊方式取件(管理员操作)，并不对用户进行扣款等操作。
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForPick success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty store id',                           
            '3' => 'no matched store order found by this storeId',                           
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForPick Success",
     * }
     * @sendSampleRequest
     */
    public function commitForPickMart() {

        $storeId         = I('request.storeId');

        if(empty($storeId))       { $this->ret(2);}

        $storeArr       = $this->getStoreArrPickMart(null, null, $storeId);

        if(empty($storeArr)) {
            $this->ret(3);
        }

        $store = array_shift($storeArr);
        $now = time();


            //send email to member

            // async send notice
            S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
                'notice_tpl' => C('NOTICE.NT_ZIPPORA_OVERDUE'),
                'member_id' => $store['toMemberId'],
                'data' => [
                    'cabinet_id' => $this->_cabinetId,
                    'pick_code' => $store['pickCode'],
                ]
            ]));

            // update o_store pick_time, pick_with
            D('ZtStore')->updateStore($store['storeId'], [
                'pick_time' => $now,
                'pick_with' => 'locker',
                'status' => 1,
            ]);
      
            //update box status
            D('CabinetBox')->releaseBox($store['boxId']);

      

        $this->ret(0);
    }

    /**
     * @apiDefine preAuthForStore
     * @apiParam {String} accessToken
     * @apiParam {String} courierId       当前登录快递员的courierId
     * @apiParam {String} toMemberId      收货人ID, 不需要传buildingId/roomId， 根据此 toMemberId可关联
     * @apiParam {String} boxModelId      选择箱体类型
     * @apiParam {String} trackingNo      快递单号(包裹时快递公司的单号)
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForStore success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty courier id',                           
            '3' => 'empty receiver\'s member id or no matched member found',                       
            '4' => 'empty box model id',                                                       
            '5' => 'empty tracking number',                                 
            '6' => 'fail to assign box for your store',                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.store
     * @apiSuccess {String}   data.store.storeId
     * @apiSuccess {String}   data.store.memberName
     * @apiSuccess {String}   data.store.buildingName
     * @apiSuccess {String}   data.store.roomName
     * @apiSuccess {String}   data.store.boxModelName
     * @apiSuccess {String}   data.store.trackingNo
     * @apiSuccess {String}   data.store.lockAddr
     * @apiSuccess {String}   data.store.boxAddr
     * @apiSuccess {String}   data.store.isAllocable
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "PreAuthForStore Success",
     *     "data": {
     *         "store": {
     *             "storeId": "10007",
     *             "memberName": "ganiks liu",
     *             "buildingName": "A001",
     *             "roomName": "1-101",
     *             "boxModelName": "Small",
     *             "trackingNo": "1234567890",
     *             "lockAddr": "3",
     *             "boxAddr": "12",
     *             "isAllocable": "1"
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function preAuthForStore() {

        $courierId     = I('request.courierId');
        $toMemberId    = I('request.toMemberId');
        $boxModelId    = I('request.boxModelId');
        $trackingNo    = I('request.trackingNo');

        if(empty($courierId))   { $this->ret(2);}
        if(empty($toMemberId)) { $this->ret(3);}
        if(empty($boxModelId)) { $this->ret(4);}
        if(empty($trackingNo)) { $this->ret(5);}

        $now = time();

        $toMember = D('OMemberOrganization')->getMember($toMemberId, $this->_cabinetId);
        if(empty($toMember)) { $this->ret(3);}

        $boxId = D('CabinetBox')->assignBox($this->_cabinetId, $boxModelId);
        if(empty($boxId)) { $this->ret(6);}

        $pickCode = \Org\Util\String::randString(6, 2);

        $holdTime = 86400;

        $store = array(
            //'store_id'
            'cabinet_id' => $this->_cabinetId,
            'box_id' => $boxId,
            'from_member_id' => $courierId,
            'store_time' => $now,
            'tracking_no' => $trackingNo,
            'to_member_id' => $toMemberId,
            'to_email' => $toMember['email'] ? : ['exp', 'null'],
            'to_phone' => $toMember['phone'] ? : ['exp', 'null'],
            'pick_code' => $pickCode,
            'pick_expire' => $now + $holdTime,
            //'pick_fee'
            //'pick_time'
            //'pick_with'
            //'clean_time'
            'create_time' => $now,
        );

        $storeId = D('OStore')->insertStore($store);

        $box = D('CabinetBox')->getBodyBox($boxId);
        $boxModel = D('CabinetBoxModel')->getByModelId($boxModelId);

        $store = [
            'storeId' => $storeId,

            'memberName' => $toMember['first_name'].' '.$toMember['last_name'],
            'buildingName' => $toMember['building_name'],
            'roomName' => $toMember['room_name'],
            'boxModelName' => $boxModel['model_name'],
            'trackingNo' => $trackingNo,

            'lockAddr' => $box['lock_addr'],
            'boxAddr' => $box['box_addr'],
            'isAllocable' => $box['is_allocable'],
        ];

        $this->ret(0, [
            'store' => $store,
        ]);
    }

    /**
     * @apiDefine preAuthForBox
     * @apiParam {String} accessToken
     * @apiParam {String} boxModelId      选择箱体类型
     * @apiParam {String} UserId          用户ID，可不填（用于共享柜的有效用户判断）
     * @apiParam {String} OrderId         商城订单ID，可不填（用于判断商城订单是否已存到柜中）
     *
     * @apiSuccess {Number} ret
            '0' => 'preAuthForBox success',                                      
            '1' => 'invalid accesstoken',                                      
            '4' => 'empty box model id',                                                       
            '6' => 'fail to assign box',
            '7' => 'order already exists',                                                       
            '8' => 'user not authorized',                     
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.box
     * @apiSuccess {String}   data.box.boxId     开箱boxID, 用于commitForStore
     * @apiSuccess {String}   data.box.lockAddr
     * @apiSuccess {String}   data.box.boxAddr
     * @apiSuccess {String}   data.box.bodySequence
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

        $OrderId    = I('request.OrderId');
        
        //增加判断共享柜有效用户才开柜，否则不开柜门
        $UserId = I('request.UserId');
        
        if(!empty($UserId)) {
           $member = D('Member')->getMemberById($UserId);
           if($member['has_credit_card'] == 0) {
           	 //unset($UserId);
             $this->ret(8);
           }
        
        }
        
        //增加商城订单如果已经存包，提示无法重复存包
        if(!empty($OrderId)) {
           $mempm = D('ZtStore')->getStore([
               'oc_order_id' => $OrderId
           ]);
           if(count($mempm)>0) {
           	 //unset($OrderId);
             $this->ret(7);
           }
        
        }
        
        //unset($UserId);
        //unset($OrderId);
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
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }

    /**
     * @apiDefine commitForStore
     * @apiParam {String} accessToken
     * @apiParam {String} courierId       当前登录快递员的courierId
     * @apiParam {String} toMemberId      收货人ID, 不需要传buildingId/roomId， 根据此 toMemberId可关联
     * @apiParam {String} boxId           来自上一步 preAuthForBox返回的boxId
     * @apiParam {String} trackingNo      快递单号(包裹时快递公司的单号)
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForStore success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty courier id',                           
            '3' => 'empty receiver\'s member id or no matched member found',                       
            '4' => 'empty box id',                                                       
            '5' => 'empty tracking number',                                 
            '6' => 'box has been ocuppied',                                 
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.store
     * @apiSuccess {String}   data.store.storeId
     * @apiSuccess {String}   data.store.memberName
     * @apiSuccess {String}   data.store.buildingName
     * @apiSuccess {String}   data.store.roomName
     * @apiSuccess {String}   data.store.boxModelName
     * @apiSuccess {String}   data.store.trackingNo
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "PreAuthForStore Success",
     *     "data": {
     *         "store": {
     *             "storeId": "10007",
     *             "memberName": "ganiks liu",
     *             "buildingName": "A001",
     *             "roomName": "1-101",
     *             "boxModelName": "Small",
     *             "trackingNo": "1234567890",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForStore() {

        $courierId     = I('request.courierId');
        $toMemberId    = I('request.toMemberId');
        $boxId         = I('request.boxId');
        $trackingNo    = I('request.trackingNo');

        if(empty($courierId))   { $this->ret(2);}
        if(empty($toMemberId))  { $this->ret(3);}
        if(empty($boxId))       { $this->ret(4);}
       //if(empty($trackingNo))  { $this->ret(5);}

        $now = time();

        $toMember = D('OMemberOrganization')->getMember($toMemberId, $this->_cabinetId);
        if(empty($toMember)) { $this->ret(3);}

        $pickCode = \Org\Util\String::randString(6, 2);

        $holdTime = 86400;

        $store = array(
            //'store_id'
            'cabinet_id' => $this->_cabinetId,
            'box_id' => $boxId,
            'courier_id' => $courierId,
            'store_time' => $now,
            'tracking_no' => $trackingNo,
            'to_member_id' => $toMemberId,
            'to_email' => $toMember['email'] ? : ['exp', 'null'],
            'to_phone' => $toMember['phone'] ? : ['exp', 'null'],
            'pick_code' => $pickCode,
            'pick_expire' => $now + $holdTime,
            //'pick_fee'
            //'pick_time'
            //'pick_with'
            //'clean_time'
            'create_time' => $now,
        );

        if(empty(D('CabinetBox')->occupyBox($boxId))) {
            $this->ret(6);
        }

        $storeId = D('OStore')->insertStore($store);

        //xg notification
        //$Xinge = new \Common\Common\Xinge();
        //$xgTitle = 'Package pickup notice';
        //$xgContent = "You have a package to arrived at zippora $this->_cabinetId, Pickup code: $pickCode.";
        //$retIOS = $Xinge->PushSingleAccountIOS(''.$toMemberId, $xgTitle, $xgContent);
        //$retAndriod = $Xinge->PushSingleAccountAndroid(''.$toMemberId, $xgTitle, $xgContent);

        //SMS notice && Email notice
        //$Notice = new \Common\Common\Notice();
        //$Notice->notice(C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'), $toMemberId, [
        //    'cabinet_id' => $this->_cabinetId,
        //    'pick_code' => $pickCode,
        //]);

        // async send notice
        S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
            'member_id' => $toMemberId,
            'data' => [
                'cabinet_id' => $this->_cabinetId,
                'pick_code' => $pickCode,
            ]
        ]));

        $box = D('CabinetBox')->getBodyBox($boxId);
        $boxModel = D('CabinetBoxModel')->getByModelId($box['box_model_id']);

        $store = [
            'storeId' => $storeId,
            'memberName' => $toMember['first_name'].' '.$toMember['last_name'],
            'buildingName' => $toMember['building_name'],
            'roomName' => $toMember['room_name'],
            'boxModelName' => $boxModel['model_name'],
            'trackingNo' => $trackingNo,
        ];

        $this->ret(0, [
            'store' => $store,
        ]);
    }

    /**
     * @apiDefine commitForSelfStore
     * @apiParam {String} accessToken
     * @apiParam {String} fromMemberId        发件人ID
     * @apiParam {String} boxId           来自上一步 preAuthForBox返回的boxId
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForSelfStore success',                                      
            '1' => 'invalid accesstoken',                                      
            '3' => 'empty member id',                                   
            '4' => 'empty box id',                                                       
            '6' => 'box has been ocuppied',                                 
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.store
     * @apiSuccess {String}   data.store.storeId
     * @apiSuccess {String}   data.store.memberName
     * @apiSuccess {String}   data.store.boxModelName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "PreAuthForStore Success",
     *     "data": {
     *         "store": {
     *             "storeId": "10007",
     *             "memberName": "ganiks liu",
     *             "boxModelName": "Small",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForSelfStore() {

        $fromMemberId    = I('request.fromMemberId');
        $boxId         = I('request.boxId');

        if(empty($fromMemberId))  { $this->ret(3);}
        if(empty($boxId))       { $this->ret(4);}

        $now = time();

        //$member = D('OMemberOrganization')->getMember($fromMemberId, $this->_cabinetId);
        //bugfix: member may not bind apartment
        $member = D('Member')->getMemberById($fromMemberId);
        //self store not consider apartment
        //if(empty($member)) { $this->ret(3);}

        $pickCode = \Org\Util\String::randString(6, 2);

        $holdTime = 0;// self store

        $store = array(
            //'store_id'
            'cabinet_id' => $this->_cabinetId,
            'box_id' => $boxId,
            'from_member_id' => $fromMemberId,
            'store_time' => $now,
            //'tracking_no' =>
            'to_member_id' => $fromMemberId,
            'pick_code' => $pickCode,
            'pick_expire' => $now + $holdTime,
            //'pick_fee'
            //'pick_time'
            //'pick_with'
            //'clean_time'
            'create_time' => $now,
        );

        if($member) {
            $store['to_email'] = $member['email'] ? : ['exp', 'null'];
            $store['to_phone'] = $member['phone'] ? : ['exp', 'null'];
        }

        if(empty(D('CabinetBox')->occupyBox($boxId))) {
            $this->ret(6);
        }

        $storeId = D('OStore')->insertStore($store);

        //xg notification
        //$Xinge = new \Common\Common\Xinge();
        //$xgTitle = 'Package pickup notice';
        //$xgContent = "You have a package to arrived at zippora $this->_cabinetId, Pickup code: $pickCode.";
        //$retIOS = $Xinge->PushSingleAccountIOS(''.$toMemberId, $xgTitle, $xgContent);
        //$retAndriod = $Xinge->PushSingleAccountAndroid(''.$toMemberId, $xgTitle, $xgContent);

        //SMS notice && Email notice
        //$Notice = new \Common\Common\Notice();
        //$Notice->notice(C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'), $toMemberId, [
        //    'cabinet_id' => $this->_cabinetId,
        //    'pick_code' => $pickCode,
        //]);

        // async send notice
        S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
            'member_id' => $fromMemberId,
            'data' => [
                'cabinet_id' => $this->_cabinetId,
                'pick_code' => $pickCode,
            ]
        ]));

        $box = D('CabinetBox')->getBodyBox($boxId);
        $boxModel = D('CabinetBoxModel')->getByModelId($box['box_model_id']);

        $store = [
            'storeId' => $storeId,
            'memberName' => D('MemberProfile')->getMemberName($fromMemberId),
            'boxModelName' => $boxModel['model_name'],
        ];

        $this->ret(0, [
            'store' => $store,
        ]);
    }

    /**
     * @apiDefine proveCode
     * @apiParam {String} accessToken
     * @apiParam {String} codeType  eg. pick
     * @apiParam {String} code
     *
     * @apiSuccess {Number} ret
            '0' => 'prove code success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty code or wrong codeType',                                      
            '3' => 'no matched pick orders',                                      
            '4' => 'wallet not enough, please recharge and try again.',                  
            '5' => 'you need to bind a credit card to your account',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.picks
     * @apiSuccess {Object}     data.picks.pick
     * @apiSuccess {String}     data.picks.pick.lockAddr
     * @apiSuccess {String}     data.picks.pick.boxAddr
     * @apiSuccess {String}     data.picks.pick.isAllocable
     * @apiSuccess {String}     data.picks.pick.storeId        订单ID，用于commitForPick
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "proveCode success",
          "data": {
            "picks": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "isAllocable" : "1",
                "storeId" : "10200"
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
        ])) { $this->ret(2);}

        switch($codeType) {
            case 'pick':
                $storeArr       = $this->getStoreArr(null, $code);
                if(empty($storeArr)) {
                    $this->ret(3);
                }
                break;
            default:
        }

        
        if(!D('Wallet')->checkWallet($storeArr[0]['toMemberId'])) {
           // if(!D('CardCredit')->checkCard($storeArr[0]['toMemberId'])) {
          //      $this->ret(5);
         //   } else {
                $money = D('Wallet')->getWallet($storeArr[0]['toMemberId']);
                $res['picks'][] = [
                           'lockAddr' => '0',
                           'boxAddr' => '0',
                           'isAllocable' => '0',
                           'storeId' => '0',
                           'money'  => $money['money'],
                     ];
                $this->ret(4,$res);
        //    }
        } 

        $cFlag = I('post.cFlag') ? True : False;

        $now = time();

        // update box.status
        foreach($storeArr as $store) {

            if($cFlag) {

                //charge store
                $box = D('CabinetBox')->getBox($store['boxId']);
                $apartment = D('OOrganization')->getApartment($this->_apartmentId);
               
               $ret = D('OCharge')->charge(
                    $store['toMemberId'],
                    $apartment['organization_id'],
                    $apartment['charge_rule'],
                    'box_penalty', [
                        'storeId' => $store['storeId'],
                        'boxModelId' => $box['box_model_id'],
                        'storeTime' => $store['storeTime'],
                    ]
                ); 

                // update o_store pick_time, pick_with
                D('OStore')->updateStore($store['storeId'], [
                    'pick_time' => $now,
                    'pick_with' => 'app',
                    'pick_fee' => $ret['amount'],
                ]); 


                D('CabinetBox')->releaseBox($store['boxId']);
           }

            $res['picks'][] = [
                'lockAddr' => $store['lockAddr'],
                'boxAddr' => $store['boxAddr'],
                'isAllocable' => $store['isAllocable'],
                'storeId' => $store['storeId'],
                'money'  => $money['money'],
            ];
        }

        $this->ret(0, $res);
    }
    
/**
     * @apiDefine proveCodePickMart
     * @apiParam {String} accessToken
     * @apiParam {String} codeType  eg. pick
     * @apiParam {String} code
     *
     * @apiSuccess {Number} ret
            '0' => 'prove code success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty code or wrong codeType',                                      
            '3' => 'no matched pick orders',                                      
            '4' => 'wallet not enough, please recharge and try again.',                  
            '5' => 'you need to bind a credit card to your account',                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.picks
     * @apiSuccess {Object}     data.picks.pick
     * @apiSuccess {String}     data.picks.pick.lockAddr
     * @apiSuccess {String}     data.picks.pick.boxAddr
     * @apiSuccess {String}     data.picks.pick.isAllocable
     * @apiSuccess {String}     data.picks.pick.storeId        订单ID，用于commitForPick
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "proveCode success",
          "data": {
            "picks": [
              {
                "lockAddr" : "00",
                "boxAddr" : "01",
                "isAllocable" : "1",
                "storeId" : "10200"
              }
            ]
          }
      }
     *
     *
     * @sendSampleRequest
     */
    public function proveCodePickMart() {

        $code = I('post.code', '', 'trim');
        $codeType = I('post.codeType', '', 'trim');

        if (empty($code) || empty($codeType) || !in_array($codeType, [
            'pick',
        ])) { $this->ret(2);}

        switch($codeType) {
            case 'pick':
                $storeArr       = $this->getStoreArrPickMart(null, $code);
                if(empty($storeArr)) {
                    $this->ret(3);
                }
                break;
            default:
        }

        $now = time();

        // update box.status
        foreach($storeArr as $store) {

                /**
                //charge store
                $box = D('CabinetBox')->getBox($store['boxId']);
                $apartment = D('OOrganization')->getApartment($this->_apartmentId);

                // update o_store pick_time, pick_with
                D('ZtStore')->updateStore($store['storeId'], [
                    'pick_time' => $now,
                    'pick_with' => 'app',
                    'pick_fee' => 0,
                ]); 

                D('CabinetBox')->releaseBox($store['boxId']);
                **/

            $res['picks'][] = [
                'lockAddr' => $store['lockAddr'],
                'boxAddr' => $store['boxAddr'],
                'isAllocable' => $store['isAllocable'],
                'storeId' => $store['storeId'],
            ];
        }

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
            '4' => 'invalid email format',                                      
            '5' => 'email not registered',                                      
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

        $wh = [
            'cabinet_id' => $this->_cabinetId,
            'pick_time' => array('exp', 'is null'),
        ];

        if ($phone) {

            //$storeArr = $this->getStoreArr(null, null, null, $phone, null, $wh);
            //$storeArrPickmart = $this->getStoreArrPickMart(null, null, null, $phone, null, $wh);
			$storeArrAsset = $this->getStoreArrAsset(null, null, null, $phone, null, $wh);

        } else if($email) {

            if (!D('Member')->isEmail($email)) $this->ret(4);
            if (empty(D('Member')->getMemberByEmail($email))) $this->ret(5);

            //$storeArr = $this->getStoreArr(null, null, null, null, $email, $wh);
            //$storeArrPickmart = $this->getStoreArrPickMart(null, null, null, null, $email, $wh);
			$storeArrAsset = $this->getStoreArrAsset(null, null, null, null, $email, $wh);

        } else {
            $this->ret(2);
        }
		
        //SMS notice && Email notice
        foreach($storeArrAsset as $store) {
        $Notice = new \Common\Common\Notice();
        $Notice->notice(C('NOTICE.NT_ASSET_HAS_PACKAGE_TO_PICK'), $store['toMemberId'], [
		    'cabinet_id' => $this->_cabinetId,
            'pick_code' => $store['pickCode'],  
        ]);
        }
        $this->ret(0,$storeArrAsset);
/*         foreach($storeArr as $store) {
            S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
                'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
                'member_id' => $store['toMemberId'],
                'data' => [
                    'cabinet_id' => $this->_cabinetId,
                    'pick_code' => $store['pickCode'],
                ]
            ]));
        }

        foreach($storeArrPickmart as $storeP) {
           S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
               'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
               'member_id' => $storeP['toMemberId'],
               'data' => [
                     'cabinet_id' => $this->_cabinetId,
                     'pick_code' => $storeP['pickCode'],
                      ]
             ]));
       }

        foreach($storeArrAsset as $storeP) {
           S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
               'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
               'member_id' => $storeP['toMemberId'],
               'data' => [
                     'cabinet_id' => $this->_cabinetId,
                     'pick_code' => $storeP['pickCode'],
                      ]
             ]));
       }
        if(empty($storeArr) && empty($storeArrPickmart)) $this->ret(3); */

        //$this->ret(0);
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
     * @apiDefine releaseblockBox
     * @apiParam {String} accessToken
     * @apiParam {String} boxId
     *
     * @apiSuccess {Number} ret
            '0' => 'release box success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty boxId',                                       
            '3' => 'release box fail',                                       
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "release box success",
          "data": {}
      }
     *
     *
     * @sendSampleRequest
     */
    public function releaseblockBox() {

        parent::releaseblockBox();
    }
    /**
     * @apiDefine initData
     * @apiParam {String} id
     *
     * @apiSuccess {Number} ret
            '0' => 'init data success',                                      
            '1' => 'invalid accesstoken',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
        {
          "ret": 0,
          "msg": "init data success",
      }
     *
     *
     * @sendSampleRequest
     */
    public function initData() {

        //update cabinet_box.status = 0
        parent::initBoxStatus();
        $this->ret(0);
    }

    private function getStoreArr($memberId, $pickCode, $storeId, $phone, $email, $extraWH=[]) {

        if($memberId) {
            $wh = ['to_member_id' => $memberId];
        } else if($pickCode) {
            $wh = ['pick_code' => $pickCode];
        } else if($phone) {
            $wh = ['to_phone' => $phone];
        } else if($email) {
            $wh = ['to_email' => $email];
        } else if($storeId) {
            $wh = ['store_id' => $storeId];
        } else if($extraWH) {
            $wh = [];
        //} else {
        //    return [];
        }

        $wh['cabinet_id'] = $this->_cabinetId;

        if($extraWH) {
            $wh = array_merge($extraWH, $wh);
        }

        $storeArr = array();
        
        $storeList= D('OStore')->getStoreList(array_merge($wh, [
            'box_id' => array('exp', 'is not null'),
            //TODO: 'status' => , // for member pick cargo from storeBox
            'pick_time' => array('exp', 'is null'),
        ]));


        foreach($storeList as $sto) {
            $box = D('CabinetBox')->getBodyBox($sto['box_id']);
            $storeArr[] = [
                'storeId'     => $sto['store_id'],
                'toMemberId'  => $sto['to_member_id'],
                'storeTime'   => $sto['store_time'],
                'pickExpire'  => $sto['pick_expire'],
                'boxId'       => $box['box_id'],
                'cabinetId'   => $box['cabinet_id'],
                'lockAddr'    => $box['lock_addr'],
                'boxAddr'     => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
                'pickCode'    => $sto['pick_code'],
            ];
        }
        return $storeArr;
    }
    //asset 取件
    private function getStoreArrAsset($memberId, $pickCode, $storeId, $phone, $email, $extraWH=[]) {

        if($memberId) {
            $wh = ['member_id' => $memberId];
        } else if($pickCode) {
            $wh = ['pickup_code' => $pickCode];
        } else if($phone) {
            $wh = ['to_phone' => $phone];
        } else if($email) {
            $wh = ['to_email' => $email];
        } else if($storeId) {
            $wh = ['rental_id' => $storeId];
        } else if($extraWH) {
            $wh = [];
        }

        $wh['cabinet_id'] = $this->_cabinetId;

        if($extraWH) {
            $wh = array_merge($extraWH, $wh);
        }
        
        $storeArr = array();

        $storeList= D('ProductRental')->getRentalList(array_merge($wh, [
            'box_id' => array('exp', 'is not null'),
            //'rental_time' => array('exp', 'is null'),
			'reture_time' => array('exp', 'is null'),
			'rental_status_code' =>'2',
			//预定
        ]));

		
        foreach($storeList as $sto) {
            $box = D('CabinetBox')->getBodyBox($sto['box_id']);
			// $wh1=$sto['product_inventory_id'];
			// $productinventory = D('ProductInventory')->getMember($wh1);
			// if($productinventory)
			// {
			  // $wh2=$productinventory['product_id'];
			  // $product= D('Product')->getMember($wh2);
			// }
            $storeArr[] = [
                'storeId'     => $sto['rental_id'],
                'toMemberId'  => $sto['member_id'],
                'storeTime'   => $sto['reserve_time'],
                'pickExpire'  => $sto['expire_time'],
                'boxId'       => $box['box_id'],
                'cabinetId'   => $box['cabinet_id'],
                'lockAddr'    => $box['lock_addr'],
                'boxAddr'     => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
                'pickCode'    => $sto['pickup_code'],
				//'rfid'    => $sto['rfid'],
				//'product_name'    => $product['product_name'],
            ];
        }
        return $storeArr;
    }
	//
    private function getStoreArrPickMart($memberId, $pickCode, $storeId, $phone, $email, $extraWH=[]) {

        if($memberId) {
            $wh = ['to_member_id' => $memberId];
        } else if($pickCode) {
            $wh = ['pick_code' => $pickCode];
        } else if($phone) {
            $wh = ['oc_to_phone' => $phone];
        } else if($email) {
            $wh = ['oc_to_email' => $email];
        } else if($storeId) {
            $wh = ['store_id' => $storeId];
        } else if($extraWH) {
            $wh = [];
        //} else {
        //    return [];
        }

        $wh['cabinet_id'] = $this->_cabinetId;

        if($extraWH) {
            $wh = array_merge($extraWH, $wh);
        }

        $storeArr = array();
        
        $storeList= D('ZtStore')->getStoreList(array_merge($wh, [
            'box_id' => array('exp', 'is not null'),
            //TODO: 'status' => , // for member pick cargo from storeBox
            'pick_time' => array('exp', 'is null'),
        ]));


        foreach($storeList as $sto) {
            $box = D('CabinetBox')->getBodyBox($sto['box_id']);
            $ocuser = D('ZtopencartOrder')->getOrder($sto['oc_order_id']);
            $storeArr[] = [
                'storeId'     => $sto['store_id'],
                'toMemberId'  => $ocuser['customer_id'],
                'storeTime'   => $sto['store_time'],
                'pickExpire'  => $sto['pick_expire'],
                'boxId'       => $box['box_id'],
                'cabinetId'   => $box['cabinet_id'],
                'lockAddr'    => $box['lock_addr'],
                'boxAddr'     => $box['box_addr'],
                'bodySequence' => $box['body_sequence'],
                'isAllocable' => $box['is_allocable'],
                'pickCode'    => $sto['pick_code'],
            ];
        }
        return $storeArr;
    }

    /**
     * @apiDefine updateSequence
     * @apiParam {String} cabinetId
     * @apiParam {String} data
{
  "cabinetId": "10001",
  "cabinets": [
    {
      "OrderID": 1,
      "bodyId": 10026,
      "cabinetType": "sub",
      "is_selected": false,
      "lockAddr": 6,
      "model": "10001"
    },
    {
      "OrderID": 2,
      "bodyId": 10021,
      "cabinetType": "sub",
      "is_selected": false,
      "lockAddr": 1,
      "model": "10001"
    },
    {
      "OrderID": 3,
      "bodyId": 10024,
      "cabinetType": "sub",
      "is_selected": false,
      "lockAddr": 4,
      "model": "10001"
    },
    {
      "OrderID": 5,
      "bodyId": 10020,
      "cabinetType": "main",
      "is_selected": false,
      "lockAddr": 0,
      "model": "10000"
    },
    {
      "OrderID": 6,
      "bodyId": 10022,
      "cabinetType": "sub",
      "is_selected": false,
      "lockAddr": 2,
      "model": "10001"
    },
    {
      "OrderID": 7,
      "bodyId": 10023,
      "cabinetType": "sub",
      "is_selected": false,
      "lockAddr": 5,
      "model": "10001"
    }
  ]
}
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                      
            '2' => 'empty cabinetId',                                      
            '3' => 'empty data',                                      
            '4' => 'cabinet not exists',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "success update 7 body sequence",
            "data": {}
        }
     *
     *
     * @sendSampleRequest
     */
    public function updateSequence() {

        $cabinetId = I('request.cabinetId');
        if(empty($cabinetId)) { $this->ret(2);}
        $cabinet = D('Cabinet')->getCabinet($cabinetId);
        if(empty($cabinet)) { $this->ret(4);}

        $data = I('request.data', '', 'trim');
        if(empty($data)) { $this->ret(3);}
        $dataArr = json_decode($data, true);

        $counter = 0;
        foreach($dataArr['cabinets'] as $c) {
            if(D('CabinetBody')->updateBodySequence($cabinetId, $c['lockAddr'], $c['OrderID'])) {
                $counter++;
            }
        }
        $this->ret(0, null, "success update $counter body sequence");
    }

    /**
     * @apiDefine getAdminCardList
     * @apiParam {String} accessToken
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                      
            '2' => 'no data',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object[]} data
     * @apiSuccess {Object}     data.card
     * @apiSuccess {String}       data.card.cardId
     * @apiSuccess {String}       data.card.rfid
     * @apiSuccess {String}       data.card.cabinetId
     * @apiSuccess {String}       data.card.zpAdminId
     * @apiSuccess {String}       data.card.zpAdminName
     * @apiSuccess {String}       data.card.zpAdminRole
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "success",
            "data": [
                {
                    "cardId": "10003",
                    "rfid": "123456",
                    "cabinetId": "10004",
                    "zpAdminId": "1",
                    "zpAdminName": null,
                    "zpAdminRole": null
                }
            ]
        }
     *
     *
     * @sendSampleRequest
     */
    public function getAdminCardList() {

        $cardList = D('CabinetAdminCard')->getAdminCardList([
            'cabinet_id' => $this->_cabinetId,
            'status' => 1,
        ]);
        if(empty($cardList)) {
            $this->ret(2);
        }
        foreach($cardList as $card) {
            $cardArr[] = [
                'cardId' => $card['card_id'],
                'rfid' => $card['rfid'],
                'cabinetId' => $card['cabinet_id'],
                'zpAdminId' => $card['zp_admin_id'],
                'zpAdminName' => $card['zp_admin_name'],
                'zpAdminRole' => $card['zp_admin_role'],
            ];
        }
        $this->ret(0, $cardArr);
    }

    /**
     * @apiDefine reportError
     *
     * @apiParam {String} accessToken
     * @apiParam {String} methodName
     * @apiParam {String} message
     * @apiParam {String} stackTrace
     * @apiParam {String} memo
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'report error success',              
            '1' => 'invalid accesstoken',               
            '2' => 'fail to report error',               
     *
     */
    public function reportError() {

        if(D('CabinetError')->insertCabinetError($this->_cabinetId, I('request.'))) {
            $this->ret(0);
        } else {
            $this->ret(2);
        }
    }

    /**
     * @apiDefine commitForOcStore
     * @apiParam {String} accessToken
     * @apiParam {String} boxId           来自上一步 preAuthForBox返回的boxId
     * @apiParam {String} ocCourierId     商城投递员的ocCourierId
     * @apiParam {String} ocOrderId       商城订单ID
     * @apiParam {String} OrderStore      商城ID
     * @apiParam {String} CustomerId      用户ID
     * @apiParam {String} OrderEmail      商城订单email
     * @apiParam {String} OrderPhone      商城订单phone
     
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForOcStore success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty courier id',                           
            '4' => 'empty box id',                                                       
            '6' => 'box has been ocuppied',                                 
            '7' => 'empty orderId',
            '8' => 'store is not empty',                       
            '9' => 'email or phone is not empty',  
            '10' => 'CustomerId is not empty',                     
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.store
     * @apiSuccess {String}   data.store.storeId
     * @apiSuccess {String}   data.store.memberName
     * @apiSuccess {String}   data.store.boxModelName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "CommitForOcStore Success",
     *     "data": {
     *         "store": {
     *             "storeId": "10007",
     *             "memberName": "ganiks liu",
     *             "boxModelName": "Small",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForOcStore() {

        $ocCourierId     = I('request.ocCourierId');
        $boxId         = I('request.boxId');
        $ocOrderId    = I('request.ocOrderId');
        
        $OrderStore    = I('request.OrderStore');
        $OrderEmail    = I('request.OrderEmail');
        $OrderPhone    = I('request.OrderPhone');
        $CustomerId    = I('request.CustomerId');

        if(empty($ocCourierId))   { $this->ret(2);}
        if(empty($boxId))       { $this->ret(4);}
        if(empty($ocOrderId))   { $this->ret(7);}
        
        if(empty($OrderStore))   { $this->ret(8);}
        if(empty($OrderEmail) && empty($OrderPhone))   { $this->ret(9);}
        if(empty($CustomerId))   { $this->ret(10);}

        $now = time();

        //$ocOrder = D('OcOrder')->getByOrderId($ocOrderId);
        //if(empty($ocOrder)) { $this->ret(8);}

        $pickCode = \Org\Util\String::randString(6, 2);

        //$holdTime = 86400;

        $store = array(
            //'store_id'
            'cabinet_id' => $this->_cabinetId,
            'box_id' => $boxId,
            'store_time' => $now,
            'oc_store_id' => $OrderStore,
            'oc_order_id' => $ocOrderId,
            'oc_courier_id' => $ocCourierId,
            //'status'
            'oc_to_email' => $OrderEmail,
            'oc_to_phone' => $OrderPhone,
            'pick_code' => $pickCode,
            //'pick_expire' => $now + $holdTime,
            //'pick_fee'
            //'pick_time'
            //'pick_with'
            //'clean_time'
            'create_time' => $now,
        );

        if(empty(D('CabinetBox')->occupyBox($boxId))) {
            $this->ret(6);
        }

        $storeId = D('ZtStore')->insertStore($store);

        // async send notice
        /**S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.ZT_MEMBER_HAS_PACKAGE_TO_PICK'),
            'oc_customer_id' => $ocOrder['customer_id'],
            'data' => [
                'cabinet_id' => $this->_cabinetId,
                'pick_code' => $pickCode,
            ]
        ])); **/
        S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
                 'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
                 'member_id' => $ocOrderId,
                 'data' => [
                 'cabinet_id' => $this->_cabinetId,
                 'pick_code' => $pickCode,
                 ]
             ]));

        //update ztopencart_order order_status_id, delivery_locker_id
        /** tpshop order isn't in ztopencart_order
        D('OcOrder')->updateOrder([
            'order_id' => $ocOrderId,
        ], [
            'delivery_locker_id' => $this->_cabinetId,
            'order_status_id' => 3,//shipped
        ]);  **/

        $box = D('CabinetBox')->getBodyBox($boxId);
        $boxModel = D('CabinetBoxModel')->getByModelId($box['box_model_id']);

        $this->ret(0, [
            'store' => [
                'storeId' => $storeId,
                'boxModelName' => $boxModel['model_name'],
                'lockAddr' => $box['lock_addr'],
                'boxAddr' => $box['box_addr'],
                'isAllocable' => $box['is_allocable'],
            ]
        ]);
    }

    /**
     * @apiDefine getOcCustomerByOcOrderId
     * @apiParam {String}   accessToken
     * @apiParam {String}   ocOrderId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get customer success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty order id',                    
            '3' => 'order not found',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.ocCustomer
     * @apiSuccess {String}     data.ocCustomer.ocCustomerId
     * @apiSuccess {String}     data.ocCustomer.ocCustomerName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Success",
     *     "data": {
     *         "ocCustomer": [
     *             {
     *                 "ocCustomerId": "10009",
     *                 "ocCustomerName": "David Washington",
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getOcCustomerByOcOrderId() {

        $ocOrderId = I('request.ocOrderId');
        if(empty($ocOrderId)) {
            $this->ret(2);
        }

        $ocOrder = D('OcOrder')->getOrder([
            'order_id' => $ocOrderId,
            'delivery_locker_id' => 1,
            'order_status_id' => 1,//pending
        ]);

        if(empty($ocOrder)) { $this->ret(3);}

        $this->ret(0, [
            'ocCustomer' => [
                'ocCustomerId' => $ocOrder['customer_id'],
                'ocCustomerName' => $ocOrder['firstname'] . ' ' .$ocOrder['lastname'],
            ]
        ]);
    }

      /**
      * @apiDefine getPlatformInfo
      * @apiParam {String}   accessToken
 
      * @apiSuccess {Number} ret
      * @apiSuccess {String} msg
             '0' => 'get data success',
             '1' => 'invalid accesstoken',
             '2' => 'data not found',
      * @apiSuccess {Object} data
      * @apiSuccess {String}     data.id
      * @apiSuccess {String}     data.imageurl
      * @apiSuccess {String}     data.apiurl
      * @apiSuccess {String}     data.name
      * @apiSuccess {String}     data.requestmethod
      * @apiSuccess {String}     data.datatype
      *
      * @apiSuccessExample {json} Success-Response:
      * {
      *     "ret": 0,
      *     "msg": "Success",
      *     "data": {
      *             [
      *             {
      *                 "id": "10",
      *                 "imageurl": "http://example.com/image.png",
      *                 "apiurl": "http://example.com/api",
      *                 "name": "example name",
      *                 "requestmethod": "Get",
      *                 "datatype": "list",
      *             }
      *         ]
      *     }
      * }
      * @apiSampleRequest
      */

    public function getPlatformInfo() {
 
         $pfOrder = D('Platform')->getInfo();

         if(empty($pfOrder)) { $this->ret(2);}
 
         foreach($pfOrder as $ord) {
             $orderArr[] = [
                 'id' => $ord['id'],
                 'imageurl' => $ord['imageurl'],
                 'apiurl' => $ord['apiurl'],
                 'name' => $ord['name'],
                 'requestmethod' => $ord['requestmethod'],
                 'datatype' => $ord['datatype'],
                      ];
        }
        $this->ret(0, $orderArr);

     }
     
    /**
     * @apiDefine getOrder
     * @apiParam {String}   accessToken
     * @apiParam {String}   OrderId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get customer data success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty order id',                    
            '3' => 'order not found',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.Customer
     * @apiSuccess {String}     data.Customer.CustomerId
     * @apiSuccess {String}     data.Customer.CustomerName
     * @apiSuccess {String}     data.Customer.CustomerEmail
     * @apiSuccess {String}     data.Customer.CustomerPhone
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Success",
     *     "data": {
     *         "ocCustomer": [
     *             {
     *                 "CustomerId": "10009",
     *                 "CustomerName": "David Washington",
     *                 "CustomerEmail": "da@qq.com",
     *                 "CustomerPhone": "13333333333",
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getOrder() {

        $OrderId = I('request.OrderId');
        if(empty($OrderId)) {
            $this->ret(2);
        }

        $Order = D('OcOrder')->getOrder([
            'order_id' => $OrderId,
            'delivery_locker_id' => 1,
            'order_status_id' => 1,//pending
        ]);

        if(empty($Order)) { $this->ret(3);}

        $this->ret(0, [
            'Customer' => [
                'CustomerId' => $Order['customer_id'],
                'CustomerName' => $Order['firstname'] . ' ' .$Order['lastname'],
                'CustomerEmail' => $Order['email'],
                'CustomerPhone' => $Order['telephone'],
            ]
        ]);
    }
    
    
    /**
     * @apiDefine getUser
     * @apiParam {String}   accessToken
     * @apiParam {String}   userId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get customer data success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty User id',                    
            '3' => 'User not found',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.tocustomer
     * @apiSuccess {String}     data.tocustomer.to_member_id
     * @apiSuccess {String}     data.tocustomer.customerName
     * @apiSuccess {String}     data.tocustomer.customerEmail
     * @apiSuccess {String}     data.tocustomer.customerPhone
     * @apiSuccess {String}     data.tocustomer.userId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Success",
     *     "data": {
     *         "tocustomer": [
     *             {
     *                 "to_member_id": "10009",
     *                 "customerName": "David Washington",
     *                 "customerEmail": "da@qq.com",
     *                 "customerPhone": "13333333333",
     *                 "userId": "12345678",
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getUser() {

        $UserId = I('request.userId');
        if(empty($UserId)) {
            $this->ret(2);
        }
        
        $UserProfile = D('MemberProfile')->getMember([
            'username' => $UserId
            ]);
            
        if(empty($UserProfile)) { $this->ret(3);}
        
        $User = D('Member')->getMemberById($UserProfile['member_id']);


        if(empty($User)) { $this->ret(3);}

        $this->ret(0, [
            'tocustomer' => [
                'to_member_id' => $User['member_id'],
                'customerName' => $UserProfile['first_name'] . ' ' .$UserProfile['last_name'],
                'customerEmail' => $User['email'],
                'customerPhone' => $User['phone'],
                'userId' => $UserProfile['username'],
            ]
        ]);
    }
    
    
    /**
     * @apiDefine commitForStoreShare
     * @apiParam {String} accessToken
     * @apiParam {String} courierId       当前登录快递员的courierId
     * @apiParam {String} toMemberId      收货人ID
     * @apiParam {String} boxId           来自上一步 preAuthForBox返回的boxId
     * @apiParam {String} trackingNo      快递单号(包裹时快递公司的单号)
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForStoreShare success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty courier id',                           
            '3' => 'empty receiver\'s member id or no matched member found',                       
            '4' => 'empty box id',                                                       
            '5' => 'empty tracking number',                                 
            '6' => 'box has been ocuppied',                                 
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.store
     * @apiSuccess {String}   data.store.storeId
     * @apiSuccess {String}   data.store.memberName
     * @apiSuccess {String}   data.store.boxModelName
     * @apiSuccess {String}   data.store.trackingNo
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "AuthForStore Success",
     *     "data": {
     *         "store": {
     *             "storeId": "10007",
     *             "memberName": "tom",
     *             "boxModelName": "Small",
     *             "trackingNo": "1234567890",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForStoreShare() {

        $courierId     = I('request.courierId');
        $toMemberId    = I('request.toMemberId');
        $boxId         = I('request.boxId');
        $trackingNo    = I('request.trackingNo');

        if(empty($courierId))   { $this->ret(2);}
        if(empty($toMemberId))  { $this->ret(3);}
        if(empty($boxId))       { $this->ret(4);}
       //if(empty($trackingNo))  { $this->ret(5);}

        $now = time();

        $toMember = D('Member')->getMemberById($toMemberId);
        $MemberProfile = D('MemberProfile')->getProfileById($toMemberId);
        if(empty($toMember)) { $this->ret(3);}

        $pickCode = \Org\Util\String::randString(6, 2);

        $holdTime = 86400;

        $store = array(
            //'store_id'
            'cabinet_id' => $this->_cabinetId,
            'box_id' => $boxId,
            'courier_id' => $courierId,
            'store_time' => $now,
            'tracking_no' => $trackingNo,
            'to_member_id' => $toMemberId,
            'to_email' => $toMember['email'] ? : ['exp', 'null'],
            'to_phone' => $toMember['phone'] ? : ['exp', 'null'],
            'pick_code' => $pickCode,
            'pick_expire' => $now + $holdTime,
						'create_time' => $now,
        );

        if(empty(D('CabinetBox')->occupyBox($boxId))) {
            $this->ret(6);
        }

        $storeId = D('OStore')->insertStore($store);
        
        //存包后更新用户存包表中状态为2已存包与存包单ID
        
        //$umstore['store_status'] = 2;
        //$umstore['store_id'] = $storeId;
        //D('MemberStore')->updateMemberStore($trackingNo,$umstore);
        
        
        //xg notification
        //$Xinge = new \Common\Common\Xinge();
        //$xgTitle = 'Package pickup notice';
        //$xgContent = "You have a package to arrived at zippora $this->_cabinetId, Pickup code: $pickCode.";
        //$retIOS = $Xinge->PushSingleAccountIOS(''.$toMemberId, $xgTitle, $xgContent);
        //$retAndriod = $Xinge->PushSingleAccountAndroid(''.$toMemberId, $xgTitle, $xgContent);

        //SMS notice && Email notice
        //$Notice = new \Common\Common\Notice();
        //$Notice->notice(C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'), $toMemberId, [
        //    'cabinet_id' => $this->_cabinetId,
        //    'pick_code' => $pickCode,
        //]);

        // async send notice
        S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
            'member_id' => $toMemberId,
            'data' => [
                'cabinet_id' => $this->_cabinetId,
                'pick_code' => $pickCode,
            ]
        ]));

        $box = D('CabinetBox')->getBodyBox($boxId);
        $boxModel = D('CabinetBoxModel')->getByModelId($box['box_model_id']);

        $store = [
            'storeId' => $storeId,
            'memberName' => $MemberProfile['first_name'].' '.$MemberProfile['last_name'],
            'boxModelName' => $boxModel['model_name'],
            'trackingNo' => $trackingNo,
        ];

        $this->ret(0, [
            'store' => $store,
        ]);
    }
    
    /**
     * @apiDefine userLogin
     * @apiParam {String}   accessToken
     * @apiParam {String}   userId
     * @apiParam {String}   userEmail
     * @apiParam {String}   userPhone
     * @apiParam {String}   userPassword

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get customer data success',                      
            '1' => 'invalid accesstoken',                                                      
            '2' => 'empty User id or Email',                    
            '3' => 'User or Password is wrong', 
            '4' => 'Email is invalid',
            '5' => 'User is not found',                                                     
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.customer
     * @apiSuccess {String}     data.customer.from_member_id
     * @apiSuccess {String}     data.customer.customerName
     * @apiSuccess {String}     data.customer.customerEmail
     * @apiSuccess {String}     data.customer.customerPhone
     * @apiSuccess {String}     data.customer.userId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Success",
     *     "data": {
     *         "customer": [
     *             {
     *                 "from_member_id": "10009",
     *                 "customerName": "David Washington",
     *                 "customerEmail": "da@qq.com",
     *                 "customerPhone": "13333333333",
     *                 "userId": "12345678",    
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function userLogin() {

        $UserId = I('request.userId', '', 'trim');
        $UserEmail = I('request.userEmail', '', 'trim');
        $UserPhone = I('request.userPhone', '', 'trim');
        $UserPWD = I('request.userPassword');
        $Member = D('member');
        
        if($UserEmail) {
            if (!$Member->isEmail($UserEmail)) $this->ret(4);
            if (!$Member->getMemberByEmail($UserEmail)) $this->ret(5);
            $member = $Member->loginEmail($UserEmail, $UserPWD);
        } else if($UserPhone) {
                if (!$Member->getMemberByPhone($UserPhone)) $this->ret(5);
                $member = $Member->loginPhone($UserPhone, $UserPWD);
        } else if($UserId) {
        	  $UP = D('MemberProfile')->getMember([
            'username' => $UserId
            ]);
            if (!$UP) $this->ret(5);
            if (!$Member->getMemberById($UP['member_id'])) $this->ret(5);
            $member = $Member->loginId($UP['member_id'], $UserPWD);
        }  else {
            $this->ret(2);
        }
        if (!$member) $this->ret(3);

        $UserProfile = D('MemberProfile')->getProfile($member['member_id']);

        $this->ret(0, [
            'customer' => [
                'from_member_id' => $member['member_id'],
                'customerName' => $UserProfile['first_name'] . ' ' .$UserProfile['last_name'],
                'customerEmail' => $member['email'],
                'customerPhone' => $member['phone'],
                'userId' => $UserProfile['username'],
            ]
        ]);
    }


}
