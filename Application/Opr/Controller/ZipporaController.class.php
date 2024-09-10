<?php
namespace Opr\Controller;
use Think\Controller;

class ZipporaController extends BaseController {

    /**
     * @api {get} /zippora/getZipporaList getZipporaList
     * @apiDescription 获取zippora首页所有待取快递
     * @apiName getZipporaList
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'get zippora list success',                                      
            '1' => 'need login',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.apartmentList
     * @apiSuccess {Object}     data.apartmentList.apartment
     * @apiSuccess {String}       data.apartmentList.apartment.organizationId     公寓ID
     * @apiSuccess {String}       data.apartmentList.apartment.organizationName   公寓名称
     * @apiSuccess {String}       data.apartmentList.apartment.address         公寓地址
     * @apiSuccess {String}       [data.apartmentList.apartment.buildingName]    building名称(弃用)
     * @apiSuccess {String}       [data.apartmentList.apartment.roomName]        room名称(弃用)
     * @apiSuccess {String}       data.apartmentList.apartment.unitName        unit名称
     * @apiSuccess {String}       data.apartmentList.apartment.chargeDay       扣费日期(1~31)
     * @apiSuccess {String}       data.apartmentList.apartment.price           价格
     * @apiSuccess {Object}       data.apartmentList.apartment.boxPenalty
     * @apiSuccess {Object}         data.apartmentList.apartment.boxPenalty.10001               10001/10002/10003为大中小三种boxModel的box_model_id
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10001.amount      一天的罚金
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10001.pay_online  是否线上支付，1代表线上，0代表线下
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10001.grace_day   免费天数(如=2，则从第3天开始收罚金)
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10001.box_model_name
     * @apiSuccess {Object}         data.apartmentList.apartment.boxPenalty.10002               10001/10002/10003为大中小三种boxModel的box_model_id
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10002.amount      一天的罚金
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10002.pay_online  是否线上支付，1代表线上，0代表线下
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10002.grace_day   免费天数(如=2，则从第3天开始收罚金)
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10002.box_model_name
     * @apiSuccess {Object}         data.apartmentList.apartment.boxPenalty.10003               10001/10002/10003为大中小三种boxModel的box_model_id
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10003.amount      一天的罚金
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10003.pay_online  是否线上支付，1代表线上，0代表线下
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10003.grace_day   免费天数(如=2，则从第3天开始收罚金)
     * @apiSuccess {String}           data.apartmentList.apartment.boxPenalty.10003.box_model_name
     * @apiSuccess {String}       data.apartmentList.apartment.approveStatus   审核状态：0待审核 pending audit; 1审核通过； 2 审核被拒绝
     * @apiSuccess {String}       data.apartmentList.apartment.zipporaCount    该公寓中zippora数量
     * @apiSuccess {Object[]}     data.apartmentList.apartment.zipporaList   该公寓中zippora列表
     * @apiSuccess {Object}         data.apartmentList.apartment.zipporaList.zippora
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.cabinetId 该zippora的快递柜ID
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.latitude  该zippora的纬度
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.longitude 该zippora的经度
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.address
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.addressUrl
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.storeCount 该zippora中订单数量
     * @apiSuccess {Object[]}         data.apartmentList.apartment.zipporaList.zippora.storeList  该zippora中订单列表
     * @apiSuccess {Object}             data.apartmentList.apartment.zipporaList.zippora.storeList.store
     * @apiSuccess {String}               data.apartmentList.apartment.zipporaList.zippora.storeList.store.storeId
     * @apiSuccess {String}               [data.apartmentList.apartment.zipporaList.zippora.storeList.store.courierCompanyName]  快递公司名称（可能为空，对自存取订单）
     * @apiSuccess {String}               data.apartmentList.apartment.zipporaList.zippora.storeList.store.pickCode  取件码
     * @apiSuccess {String}               data.apartmentList.apartment.zipporaList.zippora.storeList.store.storeTime 存件时间
     * @apiSuccess {Object[]} data.selfStoreList  该zippora中订单列表
     * @apiSuccess {Object}     data.selfStoreList.store
     * @apiSuccess {String}       data.selfStoreList.store.storeId
     * @apiSuccess {String}       data.selfStoreList.store.pickCode  取件码
     * @apiSuccess {String}       data.selfStoreList.store.storeTime 存件时间
     * @apiSuccess {String}       data.selfStoreList.store.address
     * @apiSuccess {String}       data.selfStoreList.store.cabinetId
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "Success",
            "data": {
                "apartmentList": [
                    {
                        "memberId": "10009",
                        "organizationId": "10001",
                        "organizationName": "DCW Apartment",
                        "unitName": "1-101",
                        "chargeDay": "1",
                        "boxPenalty": {
                            "10001": {
                                "amount": "5",
                                "pay_online": "1",
                                "grace_day": 2,
                                "box_model_name": "Small"
                            },
                            "10002": {
                                "amount": "1.5",
                                "pay_online": "1",
                                "grace_day": 1,
                                "box_model_name": "Middle"
                            },
                            "10003": {
                                "amount": "2",
                                "pay_online": "1",
                                "grace_day": 1,
                                "box_model_name": "Large"
                            }
                        },
                        "approveStauts": "0",
                        "zipporaCount": 1,
                        "zipporaList": [
                            {
                                "cabinetId": "10001",
                                "latitude": "35.00",
                                "longitude": "82.00",
                                "address": "Street of the Golden Lantern, Laguna Niguel, CA, United States",
                                "addressUrl": "",
                                "storeCount": 1,
                                "storeList": [
                                    {
                                        "storeId": "10001",
                                        "pickCode": "814154",
                                        "storeTime": "2017-08-07 21:42:19",
                                        "courierCompanyName": "OCCO Delivery Company"
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "memberId": "10009",
                        "organizationId": "10002",
                        "organizationName": "TTS organization",
                        "unitName": "1-105",
                        "chargeDay": "1",
                        "boxPenalty": {
                            "10001": {
                                "amount": "5",
                                "pay_online": "1",
                                "grace_day": 2,
                                "box_model_name": "Small"
                            },
                            "10002": {
                                "amount": "1.5",
                                "pay_online": "1",
                                "grace_day": 1,
                                "box_model_name": "Middle"
                            },
                            "10003": {
                                "amount": "2",
                                "pay_online": "1",
                                "grace_day": 1,
                                "box_model_name": "Large"
                            }
                        },
                        "approveStauts": "0",
                        "zipporaCount": 1,
                        "zipporaList": [
                            {
                                "cabinetId": "10002",
                                "latitude": "35.00",
                                "longitude": "82.00",
                                "address": "Street of the Golden Lantern, Laguna Niguel, CA, United States",
                                "addressUrl": "",
                                "storeCount": 0,
                                "storeList": []
                            }
                        ]
                    },
                    {
                        "memberId": "10009",
                        "organizationId": "10003",
                        "organizationName": "MMD organization",
                        "unitName": "2-102",
                        "chargeDay": "1",
                        "boxPenalty": null,
                        "approveStauts": "1",
                        "zipporaCount": 0,
                        "zipporaList": [
                            {
                                "cabinetId": "10002",
                                "latitude": "35.00",
                                "longitude": "82.00",
                                "address": "Street of the Golden Lantern, Laguna Niguel, CA, United States",
                                "addressUrl": "",
                                "storeCount": 0,
                                "storeList": []
                            }
                        ]
                    }
                ],
                "selfStoreList": [
                    {
                        "storeId": "10001",
                        "pickCode": "814154",
                        "storeTime": "2017-08-07 21:42:19",
                    }
                ]
            }
        }
     *
     * @apiSampleRequest
     */
    public function getZipporaList(){

        $list = D('OMemberOrganization')->getMemberApartmentList([
            'o_member_organization.member_id' => $this->_memberId,
            'o_member_organization.approve_status' => ['in', [1, 0]],
            'o_member_organization.status' => ['neq', 3],
            'o_member_organization.cancel_time' => 0,
        ]);
        $apartmentArr = [];
        foreach($list as $k => $m) {
            $zipporaList = [];
            $zipporaArr = D('OOrganizationCabinet')->getApartmentCabinetArr(['organization_id' => $m['organization_id']], ['cabinetId', 'latitude', 'longitude', 'address', 'address_url']);
            foreach($zipporaArr as $zippora) {
                //courier store
                $storeList = D('OStore')->getStoreArr([
                    'cabinet_id' => $zippora['cabinetId'],
                    'from_member_id' => array('exp', 'is null'),
                    'courier_id' => array('exp', 'is not null'),
                    'to_member_id' => $m['member_id'],
                    'pick_time' => array('exp', 'is null')
                ], ['storeId', 'courierCompanyName', 'pickCode', 'storeTime'], true);
                $zipporaList[] = [
                    'cabinetId' => $zippora['cabinetId'],
                    'latitude' => $zippora['latitude'],
                    'longitude' => $zippora['longitude'],
                    'address' => $zippora['address'],
                    'addressUrl' => $zippora['addressUrl'],
                    'storeCount' => count($storeList),
                    'storeList' => $storeList,
                ];
            }
            $chargeRuleArr = json_decode($m['charge_rule'], true);
            if($chargeRuleArr['box_penalty']) {
                foreach($chargeRuleArr['box_penalty'] as $ck => $boxPenalty) {
                    $chargeRuleArr['box_penalty'][$ck]['box_model_name'] = D('CabinetBoxModel')->getModelName($ck);
                }
            }
            $apartmentArr[] = [
                'memberId' => $m['member_id'],
                'organizationId' => $m['organization_id'],
                'organizationName' => $m['organization_name'],
                'address' => $m['address'],
                //'buildingName' => $m['building_name'],
                //'roomName' => $m['room_name'],
                'unitName' => $m['unit_name'],
                'chargeDay' => $m['charge_day'],
                //'price' => $m['price'],
                'price' => $chargeRuleArr['monthly_fee']['amount'] ? : 0,
                'boxPenalty' => $chargeRuleArr['box_penalty'],
                'approveStatus' => $m['approve_status'],
                'zipporaCount' => count($zipporaArr),
                'zipporaList' => $zipporaList,
            ];
        }

        $selfStoreArr = D('OStore')->getStoreArr([
            'courier_id' => array('exp', 'is null'),
            'from_member_id' => $this->_memberId,
            'to_member_id' => $this->_memberId,
            'pick_time' => array('exp', 'is null')
        ], [
            'storeId',
            'pickCode',
            'storeTime',
            'cabinetId',
            'address',
        ], true);

        $data = [
            'apartmentList' => $apartmentArr ? : [],
            'selfStoreList' => $selfStoreArr ? : [],
        ];

        $this->ret(0, $data);
    }
    
    /**
     * @api {get} /zippora/getShareZipporaList getShareZipporaList
     * @apiDescription 获取共享柜中所有待取快递
     * @apiName getShareZipporaList
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'get zippora list success',                                      
            '1' => 'need login',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.apartmentList
     * @apiSuccess {Object}     data.apartmentList.apartment
     * @apiSuccess {String}       data.apartmentList.apartment.organizationId     ID
     * @apiSuccess {String}       data.apartmentList.apartment.organizationName   名称
     * @apiSuccess {String}       data.apartmentList.apartment.address         地址
     * @apiSuccess {String}       data.apartmentList.apartment.chargeDay       扣费日期(1~31)
     * @apiSuccess {String}       data.apartmentList.apartment.price           价格
     * @apiSuccess {String}       data.apartmentList.apartment.approveStatus   审核状态：0待审核 pending audit; 1审核通过； 2 审核被拒绝
     * @apiSuccess {String}       data.apartmentList.apartment.zipporaCount    zippora数量
     * @apiSuccess {Object[]}     data.apartmentList.apartment.zipporaList   zippora列表
     * @apiSuccess {Object}         data.apartmentList.apartment.zipporaList.zippora
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.cabinetId 该zippora的快递柜ID
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.latitude  该zippora的纬度
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.longitude 该zippora的经度
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.address
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.addressUrl
     * @apiSuccess {String}           data.apartmentList.apartment.zipporaList.zippora.storeCount 该zippora中订单数量
     * @apiSuccess {Object[]}         data.apartmentList.apartment.zipporaList.zippora.storeList  该zippora中订单列表
     * @apiSuccess {Object}             data.apartmentList.apartment.zipporaList.zippora.storeList.store
     * @apiSuccess {String}               data.apartmentList.apartment.zipporaList.zippora.storeList.store.storeId
     * @apiSuccess {String}               [data.apartmentList.apartment.zipporaList.zippora.storeList.store.courierCompanyName]  快递公司名称（可能为空，对自存取订单）
     * @apiSuccess {String}               data.apartmentList.apartment.zipporaList.zippora.storeList.store.pickCode  取件码
     * @apiSuccess {String}               data.apartmentList.apartment.zipporaList.zippora.storeList.store.storeTime 存件时间
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "Success",
            "data": {
                "DomainList": [
                    {
                        
                
            }
        }
     *
     * @apiSampleRequest
     */
    public function getShareZipporaList(){

        $list = D('OMemberOrganization')->getMemberApartmentListDomain([
            'o_member_organization.member_id' => $this->_memberId,
            'o_member_organization.approve_status' => ['in', [1, 0]],
            'o_member_organization.status' => ['neq', 3],
            'o_member_organization.cancel_time' => 0,
        ]);
        $apartmentArr = [];
        $coi = 0;
        foreach($list as $k => $m) {
        	  
            $zipporaList = [];
            $zipporaArr = D('OOrganizationCabinet')->getApartmentCabinetArr(['organization_id' => $m['organization_id']], ['cabinetId', 'latitude', 'longitude', 'address', 'address_url']);
            foreach($zipporaArr as $zippora) {
                //courier store
                $storeList = D('OStore')->getStoreArr([
                    'cabinet_id' => $zippora['cabinetId'],
                    'from_member_id' => array('exp', 'is null'),
                    'courier_id' => array('exp', 'is not null'),
                    'to_member_id' => $m['member_id'],
                    'pick_time' => array('exp', 'is null')
                ], ['storeId', 'courierCompanyName', 'pickCode', 'storeTime'], true);
                $zipporaList[] = [
                    'cabinetId' => $zippora['cabinetId'],
                    'latitude' => $zippora['latitude'],
                    'longitude' => $zippora['longitude'],
                    'address' => $zippora['address'],
                    'addressUrl' => $zippora['addressUrl'],
                    'storeCount' => count($storeList),
                    'storeList' => $storeList,
                ];
            }
            $chargeRuleArr = json_decode($m['charge_rule'], true);
            if($chargeRuleArr['box_penalty']) {
                foreach($chargeRuleArr['box_penalty'] as $ck => $boxPenalty) {
                    $chargeRuleArr['box_penalty'][$ck]['box_model_name'] = D('CabinetBoxModel')->getModelName($ck);
                }
            }
       //$this->ret(0, ['store' => $zipporaList[1]['storeCount']]);
            foreach($zipporaList as $k => $zstore) { 
            if($zstore['storeCount']==0) unset($zipporaList[$k]);
            }
              if(count($zipporaList)>0) {
              $apartmentArr[] = [
                'memberId' => $m['member_id'],
                'organizationId' => $m['organization_id'],
                'organizationName' => $m['organization_name'],
                'address' => $m['address'],
                //'buildingName' => $m['building_name'],
                //'roomName' => $m['room_name'],
                'unitName' => $m['unit_name'],
                'chargeDay' => $m['charge_day'],
                //'price' => $m['price'],
                'price' => $chargeRuleArr['monthly_fee']['amount'] ? : 0,
                'boxPenalty' => $chargeRuleArr['box_penalty'],
                'approveStatus' => $m['approve_status'],
                'zipporaCount' => count($zipporaArr),
                'zipporaList' => $zipporaList,
            ];
          }
            
          
        }

        

        $data = [
            'apartmentList' => $apartmentArr ? : [],
            'selfStoreList' => $selfStoreArr ? : [],
        ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /zippora/getApartmentList getApartmentList
     * @apiDescription 根据zipcode获取公寓列表
     * @apiName getApartmentList
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   zipcode
     *
     * @apiSuccess {Number} ret
            '0' => 'get organization list success',                                      
            '1' => 'need login',                                      
            '2' => 'empty zipcode',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.apartmentList
     * @apiSuccess {Object}     data.apartmentList.apartment
     * @apiSuccess {String}       data.apartmentList.apartment.organizationId     公寓ID
     * @apiSuccess {String}       data.apartmentList.apartment.organizationName   公寓名称
     * @apiSuccess {String}       data.apartmentList.apartment.address         地址
     * @apiSuccess {String}       data.apartmentList.apartment.hasBinded       是否已经绑定该公寓，0未绑定， 1已绑定
     *
     * @apiSuccessExample {json} Success-Response:
       {
           "ret": 0,
           "msg": "Success",
           "data": {
               "apartmentList": [
                   {
                       "organizationId": "10001",
                       "organizationName": "DCW organization",
                       "address": "NO.233, New York Ave NW, Apt.303, New York City, New York, 23566",
                       "hasBinded": "1"
                   }
               ]
           }
       }
     *
     * @apiSampleRequest
     */
    public function getApartmentList(){

        $zipcode = I('request.zipcode');
        if(empty($zipcode)) $this->ret(2);

        $apartmentArr = D('OOrganization')->getApartmentArr(['zipcode' => $zipcode]);
        foreach($apartmentArr as $k => $apartment) {
            $apartmentArr[$k]['hasBinded'] = D('OMemberOrganization')->hasBind($this->_memberId, $apartment['organizationId']) ? 1: 0;
			$wh=[
		    'member_id' =>$this->_memberId,
			'organization_id'=>$apartment['organizationId'],
			'status'=>1,
            ];
			$appstatus = D('OMemberOrganization')->getMemberListN($wh);
			$apartmentArr[$k]['approve_status'] = $appstatus['approve_status'];
        }
        $data = [
            'apartmentList' => $apartmentArr ? array_values($apartmentArr) : [],
        ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /zippora/getBuildingList getBuildingList(弃用)
     * @apiDescription 根据organizationId获取building列表
     * @apiName getBuildingList
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   organizationId 

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'need login',                                                      
            '2' => 'empty organization id',                                                      
            '3' => 'no organization found',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.apartment
     * @apiSuccess {String}     data.apartment.organizationId     公寓ID
     * @apiSuccess {String}     data.apartment.organizationName   公寓名称
     * @apiSuccess {String}     data.apartment.address         地址
     * @apiSuccess {Object[]} data.buildingList
     * @apiSuccess {Object}     data.buildingList.building
     * @apiSuccess {String}       data.buildingList.building.buildingId
     * @apiSuccess {String}       data.buildingList.building.buildingName
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Building List Success",
     *     "data": {
     *         "apartment":  {
     *             "organizationId": "10001",
     *             "organizationName": "DCW organization",
     *             "address": "NO.233, New York Ave NW, Apt.303, New York City, New York, 23566"
     *         },
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

        $apartmentId = I('request.organizationId');
        if(empty($apartmentId)) $this->ret(2);

        $apartment = D('OOrganization')->getApartmentInfo($apartmentId);
        if(empty($apartment)) $this->ret(3);

        $buildingArr = D('OBuilding')->getBuildingArr(['organization_id' => $apartmentId]);

        $data = [
            'apartment' => $apartment,
            'buildingList' => $buildingArr ? array_values($buildingArr) : [],
        ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /zippora/getRoomList getRoomList(弃用)
     * @apiDescription 根据buildingId获取roomList
     * @apiName getRoomList
     * @apiGroup 21-Zippora

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   organizationId
     * @apiParam {String}   buildingId 楼栋ID，请求某楼栋内房间配置数据，必填楼栋ID

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'need login',                                                      
            '2' => 'invalid organizationId',                                           
            '3' => 'invalid buildingId',                                           
            '4' => 'no organization found',                                                      
            '5' => 'no building found',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.apartment
     * @apiSuccess {String}     data.apartment.organizationId     公寓ID
     * @apiSuccess {String}     data.apartment.organizationName   公寓名称
     * @apiSuccess {String}     data.apartment.address         地址
     * @apiSuccess {Object}   data.building
     * @apiSuccess {String}     data.building.buildingId     buildingID
     * @apiSuccess {String}     data.building.buildingName   building名称
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
     *         "apartment":  {
     *             "organizationId": "10001",
     *             "organizationName": "DCW organization",
     *             "address": "NO.233, New York Ave NW, Apt.303, New York City, New York, 23566"
     *         },
     *         "building":  {
     *             "buildingId": "10001",
     *             "buildingName": "A001"
     *         },
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

        $apartmentId = I('request.organizationId');
        $buildingId = I('request.buildingId');
        if(empty($apartmentId)) $this->ret(2);
        if(empty($buildingId)) $this->ret(3);

        $apartment = D('OOrganization')->getApartmentInfo($apartmentId);
        if(empty($apartment)) $this->ret(4);
        $building = D('OBuilding')->getBuildingInfo($buildingId);
        if(empty($building)) $this->ret(5);
        $roomArr = D('ORoom')->getRoomArr(['building_id' => $buildingId]);

        $data = [
            'apartment' => $apartment,
            'building' => $building,
            'roomList' => $roomArr ? array_values($roomArr) : [],
        ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /zippora/getUnitList getUnitList
     * @apiDescription 根据organizationId获取unitList
     * @apiName getUnitList
     * @apiGroup 21-Zippora

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   organizationId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'need login',                                                      
            '2' => 'invalid organizationId',                                           
            '3' => 'no organization found',                                                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.apartment
     * @apiSuccess {String}     data.apartment.organizationId     公寓ID
     * @apiSuccess {String}     data.apartment.organizationName   公寓名称
     * @apiSuccess {String}     data.apartment.address         地址
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
     *         "apartment":  {
     *             "organizationId": "10001",
     *             "organizationName": "DCW organization",
     *             "address": "NO.233, New York Ave NW, Apt.303, New York City, New York, 23566"
     *         },
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
        if(empty($apartmentId)) $this->ret(2);

        $apartment = D('OOrganization')->getApartmentInfo($apartmentId);
        if(empty($apartment)) $this->ret(3);

        $unitArr = D('OUnit')->getUnitArr(['organization_id' => $apartmentId]);

        $data = [
            'apartment' => $apartment,
            'unitList' => $unitArr ? array_values($unitArr) : [],
        ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /zippora/getStoreList getStoreList
     * @apiDescription 获取所有的订单
     * @apiName getStoreList
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'get store list success',                                      
            '1' => 'need login',                                      
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.storeList
     * @apiSuccess {Object}     data.storeList.store
     * @apiSuccess {String}       data.storeList.store.storeId
     * @apiSuccess {String}       data.storeList.store.courierCompanyName  快递公司名称
     * @apiSuccess {String}       data.storeList.store.pickCode  取件码
     * @apiSuccess {String}       data.storeList.store.storeTime 存件时间
     * @apiSuccess {String}       data.storeList.store.pickTime 存件时间
     * @apiSuccess {String}       data.storeList.store.cabinetId 快递柜ID
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "Success",
            "data": {
                "ret": 0,
                "msg": "Success",
                "data": {
                    "storeList": [
                        {
                            "storeId": "10083",
                            "courierCompanyName": "OCCO Delivery Company",
                            "pickCode": "917694",
                            "storeTime": "2017-09-25 02:53:00",
                            "pickTime": "",
                            "cabinetId": "10100"
                        },
                        {
                            "storeId": "10082",
                            "courierCompanyName": "OCCO Delivery Company",
                            "pickCode": "224287",
                            "storeTime": "2017-09-25 02:52:08",
                            "pickTime": "2017-09-25 03:17:54",
                            "cabinetId": "10001"
                        }
                    ]
                }
            }
     *
     * @apiSampleRequest
     */
    public function getStoreList(){

        $storeArr = D('OStore')->getStoreArr(['to_member_id' => $this->_memberId]);
        $data = [
            'storeList' => $storeArr ? : [],
        ];

        $this->ret(0, $data);
    }

    /**
     * @api {get} /zippora/bindApartment bindApartment
     * @apiDescription 增加新的公寓计划
     * @apiName bindApartment
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   organizationId
     * @apiParam {String}   [payOffline]  用户是否要线下支付给公寓管理员（1是，0否）
     * @apiParam {String}   [photoIds] 照片（一张或者多张）的md5值，逗号隔开,eg. 187ef4436122d1cc2f40dc2b92f0eba0,e5c639ea4b3706aac469718248bb0299
     *
     * @apiSuccess {Number} ret
            '0' => 'bind organization success',                                      
            '1' => 'need login',                                      
            '2' => 'empty organization id',                                      
            '3' => 'empty unit id',                                      
            '5' => 'empty photo id',                                      
            '6' => 'you have binded this organization',                             
            '7' => 'charge fail, please bind a valid credit card first and try again',                             
            '8' => 'charge fail, this organization has not set a charge rule',                             
     * @apiSuccess {String} msg
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "Bind organization success",
            "data": null
        }
     *
     * @apiSampleRequest
     */
    public function bindApartment(){

        $apartmentId   = I('request.organizationId');
        //$unitId        = I('request.unitId');
        $photoIds      = I('request.photoIds');

        if(empty($apartmentId)) { $this->ret(2);}

        //if(empty($unitId)) { $this->ret(3);}
/*         foreach(explode(',', $unitId) as $utId) {
            $unit = D('OUnit')->getUnitList([
                'unit_id' => $utId,
            ]);
            if(empty($unit)) { $this->ret(3);}
        } */

        //if(empty($photoIds)) { $this->ret(5);}

        $apartment = D('OOrganization')->getApartment($apartmentId);

        $hasBinded = D('OMemberOrganization')->hasBind($this->_memberId, $apartmentId);
        if($hasBinded) {
            $this->ret(6);
        }

        //charge apartment month price [Depreciated, use o_apartment.price]
        //charge signup fee with o_apartment.charge_rule
		//规则处先不屏蔽
        if($apartment['charge_rule']) {
            $ret = D('OCharge')->charge(
                $this->_memberId,
                $apartment['organization_id'],
                $apartment['charge_rule'],
                'signup_fee', [
                    'payOffline' => I('request.payOffline') ? true : false
                ]
            );
        } else {
            $this->ret(8);
        }

        if($photoIds) {
            $photoGroupId = D('PhotoGroup')->insertPhotoGroup($this->_memberId, $photoIds);
        }

        $now = time();

        $bind = [
            'member_id' => $this->_memberId,
            'organization_id' => $apartmentId,
            //'unit_id' => $unitId,
            //'apply_photo_group_id' => $photoGroupId,
            'apply_time' => $now,
            //'approve_time'
            'approve_time' => $now,
            //'approve_status'
            'last_charge_time' => $now,
            'charge_day' => date('j'),//没有前导0的day, 1~31
            'price' => $ret['amount'],
            'cost' => $ret['amount'],
            //'status' => $ret['amount'] > 0,//wait for payment
			'status' => 1,//wait for payment
            //'cancel_time'
            'create_time' => $now,
        ];

        $ret = D('OMemberOrganization')->insertMemberApartment($bind);

        $this->ret(0);
    }

    /**
     * @api {get} /zippora/cancelBindApartment cancelBindApartment
     * @apiDescription 取消公寓计划
     * @apiName cancelBindApartment
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   organizationId
     *
     * @apiSuccess {Number} ret
            '0' => 'cancel binding organization success',                                      
            '1' => 'need login',                                      
            '2' => 'empty organization id',                                      
            '3' => 'fail to cancel, you did not bind this organization',                                      
            '4' => 'fail to cancel, you have uncomplete order at this organization',                                      
     * @apiSuccess {String} msg
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "Cancel binding organization success",
            "data": null
        }
     *
     * @apiSampleRequest
     */
    public function cancelBindApartment(){

        $apartmentId   = I('request.organizationId');

        if(empty($apartmentId)) { $this->ret(2);}

        $hasBinded = D('OMemberOrganization')->hasBind($this->_memberId, $apartmentId);
        if(!$hasBinded) {
            $this->ret(3);
        }

        if($hasOrder) {
            $this->ret(4);
        }

        $ret = D('OMemberOrganization')->cancelMemberApartment($this->_memberId, $apartmentId);
        $this->ret(0);
    }

    /**
     * @api {get} /zippora/testNotification testNotification
     * @apiDescription 测试推送功能
     * @apiName testNotification
     * @apiGroup 21-Zippora
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   title
     * @apiParam {String}   content
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                      
            '1' => 'need login',                                      
            '2' => 'empty title or content',                                      
     * @apiSuccess {String} msg
     *
     * @apiSuccessExample {json} Success-Response:
        {
            "ret": 0,
            "msg": "Success",
            "data": null
        }
     *
     * @apiSampleRequest
     */
    public function testNotification(){

        $title   = I('request.title');
        $content   = I('request.content');

        if(empty($title) || empty($content)) { $this->ret(2);}

        $Xinge = new \Common\Common\Xinge();
        $retIOS = $Xinge->PushSingleAccountIOS(''.$this->_memberId, $title, $content);
        $retAndriod = $Xinge->PushSingleAccountAndroid(''.$this->_memberId, $title, $content);

        $this->ret(0, [
            'ios_ret' => $retIOS['ret_code'],
            'android_ret' => $retAndriod['ret_code'],
        ], 'notice success');
    }

	
}
