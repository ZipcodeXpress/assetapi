<?php
namespace Opr\Controller;
use Think\Controller;

class CourierController extends BaseController {

    /**
     * @api {get} /courier/insertCertificationMaterial 01-insertCertificationMaterial
     * @apiName insertCertificationMaterial
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   photoIds 照片（一张或者多张）的md5值，逗号隔开，eg. 187ef4436122d1cc2f40dc2b92f0eba0,e5c639ea4b3706aac469718248bb0299
     * @apiParam {String}   descriptions 照片描述（一个或者多个），竖线隔开，eg. ***|****|***
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'insert certification material success',              
            '1' => 'need login',              
            '2' => 'empty photoIds',              
            '3' => 'empty descriptions',              
     *
     *
     * @apiSampleRequest
     */
    public function insertCertificationMaterial(){

        $photoIds      = I('request.photoIds');
        $descriptions   = I('request.descriptions');

        if(empty($photoIds)) { $this->ret(2);}
        if(empty($descriptions)) { $this->ret(3);}

        if($photoIds && $descriptions) {
            $photoArr = explode(',', $photoIds);
            $descriptionArr = explode('|', $descriptions);
            foreach($photoArr as $k => $photo) {
                $materialArr[] = [
                    'photo' => $photo,
                    'description' => $descriptionArr[$k],
                ];
            }
        }

        if($materialArr) {
            if(D('ZCourierApply')->insertCourierApply([
                'courier_id' => $this->_memberId,
                'apply_material' => json_encode($materialArr),
            ])) {
                $member = D('Member')->getMemberById($this->_memberId);
                if($member['c_status'] == 1) {
                    $upd['c_status'] = 2;
                }
                D('Member')->updateMemberById($this->_memberId, $upd);

                $this->ret(0);
            }
        } else {
            $this->ret(2);
        }
    }

    /**
     * @api {get} /courier/getCertificationMaterialList 02-getCertificationMaterialList
     * @apiName getCertificationMaterialList
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get certification material list success',              
            '1' => 'need login',              
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.materialList
     * @apiSuccess {Object}     data.materialList.material
     * @apiSuccess {String}       data.materialList.material.photo
     * @apiSuccess {String}       data.materialList.material.description
     *
     * @apiSampleRequest
     */
    public function getCertificationMaterialList(){
        $material = D('ZCourierApply')->getCourierApplyByMemberId($this->_memberId);
        $this->ret(0, ['materialList' => json_decode($material['apply_material'], true)]);
    }

    /**
     * @api {get} /courier/insertStartingZip 11-insertStartingZip
     * @apiName insertStartingZip
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   zipcode
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'insert starting zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
     *
     * @apiSampleRequest
     */
    public function insertStartingZip(){
        $zipcode = I('request.zipcode');
        if(empty($zipcode)) {
            $this->ret(2);
        }

        if(D('ZCourierZipcode')->insertCourierZipcode([
            'courier_id' => $this->_memberId,
            'zipcode' => $zipcode,
            'type' => 'starting',
        ])){
            $this->ret(0);
        }
    }

    /**
     * @api {get} /courier/insertDestinationZip 12-insertDestinationZip
     * @apiName insertDestinationZip
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   zipcode
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'insert starting zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
     *
     * @apiSampleRequest
     */
    public function insertDestinationZip(){
        $zipcode = I('request.zipcode');
        if(empty($zipcode)) {
            $this->ret(2);
        }

        if(D('ZCourierZipcode')->insertCourierZipcode([
            'courier_id' => $this->_memberId,
            'zipcode' => $zipcode,
            'type' => 'destination',
        ])){
            $this->ret(0);
        }
    }

    /**
     * @api {get} /courier/deleteStartingZip 13-deleteStartingZip
     * @apiName deleteStartingZip
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   zipcode
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'delete starting zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
            '3' => 'no matched zipcode found',              
     *
     * @apiSampleRequest
     */
    public function deleteStartingZip(){
        $zipcode = I('request.zipcode');
        if(empty($zipcode)) {
            $this->ret(2);
        }

        if(D('ZCourierZipcode')->deleteCourierZipcode([
            'courier_id' => $this->_memberId,
            'zipcode' => $zipcode,
            'type' => 'starting',
        ])){
            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }

    /**
     * @api {get} /courier/deleteDestinationZip 14-deleteDestinationZip
     * @apiName deleteDestinationZip
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   zipcode
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'delete starting zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
            '3' => 'no matched zipcode found',              
     *
     * @apiSampleRequest
     */
    public function deleteDestinationZip(){
        $zipcode = I('request.zipcode');
        if(empty($zipcode)) {
            $this->ret(2);
        }

        if(D('ZCourierZipcode')->deleteCourierZipcode([
            'courier_id' => $this->_memberId,
            'zipcode' => $zipcode,
            'type' => 'destination',
        ])){
            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }

    /**
     * @api {get} /courier/getZipList 15-getZipList
     * @apiName getZipList
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Object}   data
     * @apiSuccess {String[]}     data.startingZipList
     * @apiSuccess {String}       data.startingZipList.zipcode
     * @apiSuccess {String[]}     data.destinationZipList
     * @apiSuccess {String}       data.destinationZipList.zipcode
     * @apiSuccess {String} msg
            '0' => 'get zip list success',              
            '1' => 'need login',              
     *
     * @apiSampleRequest
     */
    public function getZipList(){
        $data = [
            'startingZipList' => D('ZCourierZipcode')->getCourierZipcodeList($this->_memberId),
            'destinationZipList' => D('ZCourierZipcode')->getCourierZipcodeList($this->_memberId, 'destination'),
        ];
        $this->ret(0, $data);
    }

    /**
     * @api {get} /courier/getOrderCountSummary 21-getOrderCountSummary
     * @apiName getOrderCountSummary
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Object}   data
     * @apiSuccess {Object}     data.orderCountSummary
     * @apiSuccess {String}       data.orderCountSummary.countAll
     * @apiSuccess {String}       data.orderCountSummary.countToPick
     * @apiSuccess {String}       data.orderCountSummary.countToPut
     * @apiSuccess {String} msg
            '0' => 'get order count summary success',              
            '1' => 'need login',              
     *
     * @apiSampleRequest
     */
    public function getOrderCountSummary(){
        $data = [
            'orderCountSummary' => [
                'countAll' => D('ZCourierOrder')->countCourierOrder($this->_memberId),
                'countToPick' => D('ZCourierOrder')->countCourierOrder($this->_memberId, 1),
                'countToPut' => D('ZCourierOrder')->countCourierOrder($this->_memberId, 2),
            ],
        ];
        $this->ret(0, $data);
    }

    /**
     * @api {get} /courier/getOrderList 22-getOrderList
     * @apiDescription 快递员抢单之后的订单列表以及订单详情
     * @apiName getOrderList
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   [type]  order type: all(default), pick, put, finished
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Object}   data
     *   @apiSuccess {Object[]}   data.list
     *     @apiSuccess {Object}     data.list.deliver
     *       @apiSuccess {String}     data.list.deliver.deliverId 订单编号
     *       @apiSuccess {String}     data.list.deliver.orderId  快递员接单订单编号(用于cancelOrder)
     *       @apiSuccess {String}     data.list.deliver.takeTime  接单时间
     *       @apiSuccess {String}     data.list.deliver.pickTime  pick时间
     *       @apiSuccess {String}     data.list.deliver.putTime   put时间
     *       @apiSuccess {String}     data.list.deliver.putDist   送到目的地的距离
     *       @apiSuccess {String}     data.list.deliver.fee  佣金
     *       @apiSuccess {String}     data.list.deliver.canComplain  是否可申诉(快递员取货后申诉货物不对或者报告自己丢失)
     *       @apiSuccess {String}     data.list.deliver.canCancel  是否可取消(只有刚接单之后8小时内是可以取消的)
     *       @apiSuccess {Object}     data.list.deliver.cargo
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoCode 货物码
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoCodeUrl 货物码条码图片url
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoTypeName 货物类别
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoWeight   货物重量
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoWorth    货物估值
     *         @apiSuccess {String[]}   data.list.deliver.cargo.deliverPhoto  货物照片URL数组
     *       @apiSuccess {Object}     data.list.deliver.from
     *         @apiSuccess {String}     data.list.deliver.from.memberId 发货人ID
     *         @apiSuccess {String}     data.list.deliver.from.name 发货人姓名
     *         @apiSuccess {String}     data.list.deliver.from.phone 发货人电话
     *         @apiSuccess {String}     data.list.deliver.from.cabinetId 发货快递柜ID
     *         @apiSuccess {Object}     data.list.deliver.from.longitude
     *         @apiSuccess {Object}     data.list.deliver.from.latitude
     *         @apiSuccess {String}     data.list.deliver.from.address 发货快递柜地址
     *         @apiSuccess {Object}     data.list.deliver.from.box
     *           @apiSuccess {String}     data.list.deliver.from.box.boxId
     *           @apiSuccess {String}     data.list.deliver.from.box.boxModelName
     *       @apiSuccess {Object}     data.list.deliver.to
     *         @apiSuccess {String}     [data.list.deliver.to.memberId] 收货人ID(可以为空)
     *         @apiSuccess {String}     data.list.deliver.to.name 收货人姓名
     *         @apiSuccess {String}     data.list.deliver.to.phone 收货人电话
     *         @apiSuccess {String}     data.list.deliver.to.cabinetId  收货快递柜ID
     *         @apiSuccess {Object}     data.list.deliver.to.longitude
     *         @apiSuccess {Object}     data.list.deliver.to.latitude
     *         @apiSuccess {String}     data.list.deliver.to.address 收货快递柜地址
     *         @apiSuccess {Object}     data.list.deliver.to.box
     *           @apiSuccess {String}     data.list.deliver.to.box.boxId
     *           @apiSuccess {String}     data.list.deliver.to.box.boxModelName
     *       @apiSuccess {Object[]}   data.list.deliver.deliverTraceList 订单跟踪信息数组
     *         @apiSuccess {Object}     data.list.deliver.deliverTraceList.track 订单跟踪记录
     *         @apiSuccess {String}     data.list.deliver.deliverTraceList.track.time 跟踪时间
     *         @apiSuccess {String}     data.list.deliver.deliverTraceList.track.text 跟踪状态详情
     *       @apiSuccess {String} msg
                  '0' => 'get order list success',              
                  '1' => 'need login',              
                  '2' => 'wrong order type',              
     *
     *       @apiSampleRequest
     */
    public function getOrderList(){
        $type = I('request.type') ? : 'all';

        $wh = ['courier_id'=>$this->_memberId];
        switch($type) {
            case 'all':
                break;
            case 'pick':
                $wh['status'] = C('z_deliver_status_code.token_success');
                break;
            case 'put':
                $wh['status'] = C('z_deliver_status_code.fetch_success');
                break;
            case 'finished':
                $wh['status'] = C('z_deliver_status_code.pick_success');
                break;
            default:
                $this->ret(2);
        }
        $orderList= D('ZCourierOrder')->getCourierOrderList($wh);
        $list = array();

        foreach($orderList as $o) {
            $d = D('ZDeliver')->getByDeliverId($o['deliver_id']);
            if($d) {
                $list[] = D('ZDeliver')->formatDeliver($d, 'courierGetOrderList', $o);
            }
        }

        $data['list'] = $list;
        $this->ret(0, $data);
    }

    /**
     * @api {get} /courier/getDeliverList 23-getDeliverList
     * @apiDescription 等待被抢的订单列表以及订单详情
     * @apiName getDeliverList
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   [fromCabinetId] 如果只看从某个快递柜出发的订单，则需要指定 fromCabinetId
     * @apiParam {String}   longitude 快递员当前所在位置经度
     * @apiParam {String}   latitude 快递员当前所在位置纬度
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Object}   data
     *   @apiSuccess {Object[]}   data.list
     *     @apiSuccess {Object}     [data.from]   如果指定了fromCabinetId, 则from信息在 data.from; 否则在 data.list.deliver.from
     *       @apiSuccess {String}     data.from.memberId 发货人ID
     *       @apiSuccess {String}     data.from.name 发货人姓名
     *       @apiSuccess {String}     data.from.phone 发货人电话
     *       @apiSuccess {String}     data.from.cabinetId 发货快递柜ID
     *       @apiSuccess {String}     data.from.address 发货快递柜地址
     *     @apiSuccess {Object}     data.list.deliver
     *       @apiSuccess {String}     data.list.deliver.deliverId 订单编号
     *       @apiSuccess {String}     data.list.deliver.pickDist  从当前快递员所在位置到取货快递柜的距离
     *       @apiSuccess {String}     data.list.deliver.putDist   送到目的地的距离
     *       @apiSuccess {String}     data.list.deliver.fee  佣金
     *       @apiSuccess {String}     data.list.deliver.createTime 下单时间
     *       @apiSuccess {Object}     data.list.deliver.cargo
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoCode 货物码
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoCodeUrl 货物码条码图片url
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoTypeName 货物类别
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoWeight   货物重量
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoWorth    货物估值
     *         @apiSuccess {String[]}   data.list.deliver.cargo.deliverPhoto  货物照片URL数组
     *       @apiSuccess {Object}     [data.list.deliver.from]   如果指定了fromCabinetId, 则from信息在 data.from; 否则在 data.list.deliver.from
     *         @apiSuccess {String}     data.list.deliver.from.memberId 发货人ID
     *         @apiSuccess {String}     data.list.deliver.from.name 发货人姓名
     *         @apiSuccess {String}     data.list.deliver.from.phone 发货人电话  getDeliverList中收货人和发货人电话对快递员保密
     *         @apiSuccess {String}     data.list.deliver.from.cabinetId 发货快递柜ID
     *         @apiSuccess {String}     data.list.deliver.from.address 发货快递柜地址
     *       @apiSuccess {Object}     data.list.deliver.to
     *         @apiSuccess {String}     [data.list.deliver.to.memberId] 收货人ID(可以为空)
     *         @apiSuccess {String}     data.list.deliver.to.name 收货人姓名
     *         @apiSuccess {String}     data.list.deliver.to.phone 收货人电话  getDeliverList中收货人和发货人电话对快递员保密
     *         @apiSuccess {String}     data.list.deliver.to.cabinetId  收货快递柜ID
     *         @apiSuccess {String}     data.list.deliver.to.address 收货快递柜地址
     *       @apiSuccess {Object[]}   data.list.deliver.deliverTraceList 订单跟踪信息数组
     *         @apiSuccess {Object}     data.list.deliver.deliverTraceList.track 订单跟踪记录
     *         @apiSuccess {String}     data.list.deliver.deliverTraceList.track.time 跟踪时间
     *         @apiSuccess {String}     data.list.deliver.deliverTraceList.track.text 跟踪状态详情
     *       @apiSuccess {String} msg
                  '0' => 'get order count summary success',              
                  '1' => 'need login',              
                  '2' => 'empty longitude or latitude',              
     *
     *       @apiSampleRequest
     */
    public function getDeliverList(){

        $longitude = I('request.longitude');
        $latitude = I('request.latitude');
        if(empty($longitude) || empty($latitude)) {
            $this->ret(2);
        }

        $list = array();

        $fromCabinetId = I('request.fromCabinetId');
        if($fromCabinetId) {
            $deliverList= D('ZDeliver')->getDeliverList([
                'status' => C('z_deliver_status.store_success'),
                'courier_id' => ['exp', 'is null'],
                'from_cabinet_id' => $fromCabinetId,
            ]);
            foreach($deliverList as $d) {
                if($d['to_cabinet_id'] != $d['from_cabinet_id']) {
                    $list[] = D('ZDeliver')->formatDeliver($d, 'courierGetDeliverListFromCabinet', [], $latitude, $longitude);
                }
            }
            $data['from'] = $d ? D('ZDeliver')->formatDeliver($d, 'courierGetDeliverFrom') : [];
        } else {
            $deliverList= D('ZDeliver')->getDeliverList([
                'status' => C('z_deliver_status_code.store_success'),
                'courier_id' => ['exp', 'is null'],
            ]);
            foreach($deliverList as $d) {
                if($d['to_cabinet_id'] != $d['from_cabinet_id']) {
                    $list[] = D('ZDeliver')->formatDeliver($d, 'courierGetDeliverList', [], $latitude, $longitude);
                }
            }
        }

        $data['list'] = $list;

        $this->ret(0, $data);
    }

    /**
     * @api {get} /courier/getCabinetList 24-getCabinetList
     * @apiName getCabinetList
     * @apiGroup 23-Courier
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId

     * @apiParam {String} latitude   纬度
     * @apiParam {String} longitude  经度
     * @apiParam {String} [range]    距离范围
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Number} msg
            '0' => 'success',                   
            '1' => 'need login',                   
            '2' => 'empty longitude or latitude',                   
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.cabinetList
     * @apiSuccess {Object}     data.cabinetList.cabinet
     * @apiSuccess {String}       data.cabinetList.cabinet.cabinetId          cabinetId: 快递柜ID
     * @apiSuccess {String}       data.cabinetList.cabinet.deliverListCount   该快递柜符合条件的deliver待抢订单的数量
     * @apiSuccess {String}       data.cabinetList.cabinet.longitude
     * @apiSuccess {String}       data.cabinetList.cabinet.latitude
     * @apiSuccess {String}       data.cabinetList.cabinet.address
     *
     * @apiSampleRequest
     */
    public function getCabinetList(){

        //地图查找
        $latitude = I('request.latitude');
        $longitude = I('request.longitude');
        $range = I('request.range', 0);
        if(empty($longitude) || empty($latitude)) {
            $this->ret(2);
        }

        $cabinetList = D('Cabinet')->getCabinetList();

        $cbList = array();
        $Util = new \Common\Common\Util();
        foreach ($cabinetList as $value) {

            $dis = $Util->getDistance($latitude, $longitude, $value['latitude'], $value['longitude']); //计算与快递柜的距离
            if ($range > 0 && ($dis > $range)) { //过滤超过距离限制的快递柜
                continue;
            }

            $cbList[] = array(
                'cabinetId' => $value['cabinet_id'],
                //TODO: toZipcode
                'deliverListCount' => D('ZDeliver')->countByFromCabinetId($value['cabinet_id'], [
                    'status' => C('z_deliver_status_code.store_success'),
                    'courier_id' => ['exp', 'is null'],
                ]),
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude'],
                'address' => $value['address'],
            );
        }

        $data['cabinetList'] = $cbList ? : [];
        $this->ret(0, $data);
    }

    /**
     * @api {get} /courier/takeOrder 31-takeOrder
     * @apiName takeOrder
     * @apiGroup 23-Courier
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} deliverId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Number} msg
            '0' => 'take order success',                   
            '1' => 'need login',                   
            '2' => 'empty deliverId',                   
            '3' => 'no matched deliver found',                   
            '4' => 'wrong deliver status',                   
            '5' => 'fail to take the order',                   
     * @apiSuccess {Object} data
     *
     * @apiSampleRequest
     */
    public function takeOrder(){
        $deliverId = I('request.deliverId');
        if(empty($deliverId)) {
            $this->ret(2);
        }
        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) {
            $this->ret(3);
        }
        if($deliver['status'] != C('z_deliver_status_code.store_success')) {
            $this->ret(4);
        } else {
            if(D('ZCourierOrder')->insertCourierOrder([
                'courier_id' => $this->_memberId,
                'deliver_id' => $deliverId,
                'status' => C('z_deliver_status_code.token_success'),
                'fee_total' => C('z_courier_bonus_rate') * $deliver['fee_total'],
                'create_time' => time(),
            ])) {
                D('ZDeliver')->updateDeliverStatus($deliverId, 'token_success', [
                    'courier_id' => $this->_memberId,
                ]);
                $this->ret(0);
            } else {
                $this->ret(5);
            }
        }
    }

    /**
     * @api {get} /courier/cancelOrder 32-cancelOrder
     * @apiName cancelOrder
     * @apiGroup 23-Courier
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId
     * @apiParam {String}       [deliverId] deliverId和orderId不能同时为空
     * @apiParam {String}       [orderId] deliverId和orderId不能同时为空
     * @apiParam {String}       [reason]

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
            '0' => 'cancel order success',                                                  
            '1' => 'need login',                    
            '2' => 'empty deliver id order order id',                  
            '3' => 'no matched order found',                                                    
            '4' => 'wrong order status',            
     *
     * @apiSampleRequest
     */
    public function cancelOrder() {

        $deliverId = I('get.deliverId');
        $orderId = I('get.orderId');

        if($orderId) {

            $order = D('ZCourierOrder')->getCourierOrder([
                'order_id' => $orderId,
            ]);
            $deliverId = $order['deliver_id'];
        } else if($deliverId) {

            $order = D('ZCourierOrder')->getCourierOrder([
                'deliver_id' => $deliverId,
                'courier_id' => $this->_memberId,
                'status' => C('z_deliver_status_code.token_success'),
            ]);
            $orderId = $order['order_id'];
        } else {

            $this->ret(2);
        }

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) { $this->ret(3);}

        if($deliver['status'] !== C('z_deliver_status_code.token_success')) {
            $this->ret(4);
        }

        D('ZDeliver')->updateDeliverStatus($deliverId, 'token_cancel');

        D('ZCourierOrder')->updateOrderStatus($orderId, 'token_cancel', [
            'cancel_time' => time(),
            'cancel_reason' => I('get.reason') ? : ['exp', 'null'],
        ]);

        $this->ret(0);
    }

    /**
     * @api {get} /courier/version 41-version
     * @apiName version
     * @apiGroup 23-Courier
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
        $ios = $AppVersion->getLatestVersionByPlatform('ios', 'courier');
        $ado = $AppVersion->getLatestVersionByPlatform('android', 'courier');
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

    /**
     * @api {get} /courier/complainFetch 51-complainFetch
     * @apiName complainFetch
     * @apiGroup 23-Courier
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId
     * @apiParam {String}       deliverId
     * @apiParam {String}       photoIds
     * @apiParam {String}       content

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
            '0' => 'complain success',                                                  
            '1' => 'need login',                                                 
            '2' => 'empty deliver id',                                                 
            '3' => 'no matched order found',                                                   
            '4' => 'empty content',                                                 
            '5' => 'empty photoIds',                                                 
            '6' => 'wrong order status',                                                 
     *
     * @apiSampleRequest
     */
    public function complainFetch() {

        $deliverId = I('get.deliverId');
        if(empty($deliverId)) { $this->ret(2);}

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) { $this->ret(3);}

        $content = I('get.content');
        if(empty($content)) { $this->ret(4);}

        if($deliver['status'] !== C('z_deliver_status_code.fetch_success')) {
            $this->ret(6);
        }

        $photoIds = I('request.photoIds');
        if($photoIds) {
            $photoGroupId = D('PhotoGroup')->insertPhotoGroup($this->_memberId, $photoIds);
        } else {
            $this->ret(5);
        }

        if(D('Complain')->insertComplain([
            'member_id' => $this->memberId,
            'complain_photo_group_id' => $photoGroupId,
            'complain_content' => $content,
            'order_id' => $deliverId,
            'order_type' => 'ziplocker_fetch',
        ])) {
            D('ZDeliver')->updateDeliverStatus($deliverId, 'fetch_complain');
        }

        $this->ret(0);
    }

    /**
     * @api {get} /courier/complainLost 52-complainLost
     * @apiDescription 快递员取包裹后丢失包裹上报
     * @apiName complainLost
     * @apiGroup 23-Courier
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId
     * @apiParam {String}       deliverId

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
            '0' => 'complain success',                                                  
            '1' => 'need login',                                                 
            '2' => 'empty deliver id',                                                 
            '3' => 'no matched order found',                                                   
            '6' => 'wrong order status',                                                 
     *
     * @apiSampleRequest
     */
    public function complainLost() {

        $deliverId = I('get.deliverId');
        if(empty($deliverId)) { $this->ret(2);}

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) { $this->ret(3);}

        if($deliver['status'] !== C('z_deliver_status_code.fetch_success')) {
            $this->ret(6);
        }

        if(D('Complain')->insertComplain([
            'member_id' => $this->_memberId,
            'order_id' => $deliverId,
            'order_type' => 'ziplocker_lost',
        ])) {
            D('ZDeliver')->updateDeliverStatus($deliverId, 'fetch_lost');
        }

        $this->ret(0);
    }

    /**
     * @api {get} /courier/insertLocation 61-insertLocation
     * @apiName insertLocation
     * @apiGroup 23-Courier
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   longitude
     * @apiParam {String}   latitude
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'insert location success',              
            '1' => 'need login',              
            '2' => 'empty longitude',              
            '3' => 'empty latitude',              
     *
     * @apiSampleRequest
     */
    public function insertLocation(){

        $longitude = I('request.longitude');
        if(empty($longitude)) {
            $this->ret(2);
        }

        $latitude = I('request.latitude');
        if(empty($latitude)) {
            $this->ret(3);
        }

        if(D('ZCourierLocation')->insertCourierLocation([
            'courier_id' => $this->_memberId,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ])){
            $this->ret(0);
        }
    }
}
