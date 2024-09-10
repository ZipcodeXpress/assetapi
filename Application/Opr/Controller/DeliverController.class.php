<?php
namespace Opr\Controller;
use Think\Controller;

class DeliverController extends BaseController {

    /**
     * @api {get} /deliver/getDeliverPrice getDeliverPrice
     * @apiName getDeliverPrice
     * @apiGroup 14-Deliver

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiParam {String}   [cargoTypeId] 货物类别ID，来自getCargoConfig
     * @apiParam {String}   [cargoWeight] 用户自定义货物重量
     * @apiParam {String}   [cargoWorth]  用户自定义货物价值

     * @apiParam {String}   boxModelId  用户选用的箱体型号ID，来自getCargoConfig

     * @apiParam {String}   fromCabinetId 发货快递柜ID
     * @apiParam {String}   toCabinetId 收货快递柜ID
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get deliver price success',                      
            '1' => 'need login',                      
            '2' => 'wrong param',                      
     * @apiSuccess {Object} data
     * @apiSuccess {String} data.price 计算出的本订单价格
     *
     * @apiSampleRequest
     */
    public function getDeliverPrice() {

        $priceArr = D('ZDeliver')->calDeliverPrice(array_filter(I('request.')));
        if($priceArr) {
            $this->ret(0, $priceArr);
        } else {
            $this->ret(2);
        }
    }

    /**
     * @api {get} /deliver/insertDeliver insertDeliver
     * @apiName insertDeliver
     * @apiGroup 14-Deliver

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiParam {String}   boxModelId  用户选用的箱体型号ID，来自getCargoConfig

     * @apiParam {String}   fromZipcode
     * @apiParam {String}   toZipcode

     * @apiParam {String}   [toPhone] 收货人phone, email不可同时为空
     * @apiParam {String}   [toEmail] 收货人phone, email不可同时为空

     * @apiParam {String}   [photoIds] 照片（一张或者多张）的md5值，逗号隔开,eg. 187ef4436122d1cc2f40dc2b92f0eba0,e5c639ea4b3706aac469718248bb0299
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'insert deliver success',                                                  
            '1' => 'need login',                                                  
            '2' => 'wallet not enough, please recharge and try again.',                  
            '3' => 'you need to bind a credit card to your account',                  
            '5' => 'empty box model id',                                                       
            '6' => 'empty zipcode',                                                   
            '7' => 'empty destination zipcode',                                                   
            '8' => 'empty receiver phone',                                                   
            '12' => 'fail to assign a box from the origin cabinet, try another cabinet or other box model',                                                   
            '13' => 'fail to assign a box from the destination cabinet, try another cabinet or other box model',                                                   
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.deliverId 新添加的订单ID
     * @apiSuccess {String}   data.boxModelName
     * @apiSuccess {String}   data.toPhone
     * @apiSuccess {String}   data.toEmail
     * @apiSuccess {String}   data.price
     * @apiSuccess {Object}   data.from
     * @apiSuccess {String}     data.from.zipcode
     * @apiSuccess {String}     data.from.cabinetId
     * @apiSuccess {String}     data.from.address
     * @apiSuccess {String}     data.from.latitude
     * @apiSuccess {String}     data.from.longitude
     * @apiSuccess {Object}   data.to
     * @apiSuccess {String}     data.to.zipcode
     * @apiSuccess {String}     data.to.cabinetId
     * @apiSuccess {String}     data.to.address
     * @apiSuccess {String}     data.to.latitude
     * @apiSuccess {String}     data.to.longitude
     *
     * @apiSampleRequest
     */
    public function insertDeliver() {

        if(!D('Wallet')->checkWallet($this->_memberId)) {
            if(!D('CardCredit')->checkCard($this->_memberId)) {
                $this->ret(3);
            } else {
                $this->ret(2);
            }
        }

        $boxModelId    = I('request.boxModelId');
        $fromZipcode   = I('request.fromZipcode');
        $toZipcode     = I('request.toZipcode');

        if(empty($boxModelId))   { $this->ret(5);}
        if(empty($fromZipcode))  { $this->ret(6);}
        if(empty($toZipcode))    { $this->ret(7);}

        $toPhone       = I('request.toPhone');
        $toEmail       = I('request.toEmail');
        $photoIds      = I('request.photoIds');

        if($toPhone) {
            $toMember = D('Member')->getMemberByPhone($toPhone);
        } else if($toEmail) {
            $toMember = D('Member')->getMemberByEmail($toEmail);
        } else {
            $this->ret(8);
        }


        list($fromCabinetId, $fromBoxId) = D('CabinetBox')->assignCabinetAndBox($fromZipcode, $boxModelId);
        if(empty($fromBoxId)) { $this->ret(12);}

        list($toCabinetId, $toBoxId) = D('CabinetBox')->assignCabinetAndBox($toZipcode, $boxModelId);
        if(empty($toBoxId)) {
            D('CabinetBox')->releaseBox($fromBoxId);
            $this->ret(13);
        }

        $price = D('ZDeliver')->calDeliverPrice([
            'boxModelId' => $boxModelId,
            'fromCabinetId' => $fromCabinetId,
            'toCabinetId' => $toCabinetId,
        ]);

        //TODO: conside same code !
        $cargoCode = mt_rand(100000, 999999);
        $pickCode = \Org\Util\String::randString(6, 2);

        if($photoIds) {
            $photoGroupId = D('PhotoGroup')->insertPhotoGroup($this->_memberId, $photoIds);
        }

        $deliver = array(
            #'deliver_id' =>
            #'cargo_type_id' => 
            #'cargo_worth' => 
            #'cargo_weight' => 
            'box_model_id' => $boxModelId,

            'from_member_id' => $this->_memberId,
            'from_zipcode' => $fromZipcode,
            'from_cabinet_id' => $fromCabinetId,
            'from_box_id' => $fromBoxId,

            'to_member_id' => $toMember ? $toMember['member_id'] : array('exp', 'null'),
            'to_phone' => $toPhone ? : ($toMember ? $toMember['phone'] : array('exp', 'null')),
            'to_email' => $toEmail ? : ($toMember ? $toMember['email'] : array('exp', 'null')),
            #'to_name' => 
            'to_zipcode' => $toZipcode,
            'to_cabinet_id' => $toCabinetId,
            'to_box_id' => $toBoxId,

            'deliver_photo_group_id' => isset($photoGroupId) ? $photoGroupId : ['exp', 'null'],
            'dist' => D('Cabinet')->getDistance($fromCabinetId, $toCabinetId),

            'fee_dist' => $price['distFee'],
            'fee_box' => $price['boxFee'],
            'fee_total' => $price['totalFee'],
            #'remark' =>
            'cargo_code' => $cargoCode,//generated
            'pick_code' => $pickCode,//generated

            'create_time' => time(),
            #'update_time' =>

            'status' => C('z_deliver_status_code.pay_wait'),
            #'courier_id' =>
        );

        $deliverId = D('ZDeliver')->insertDeliver($deliver);

        $fromCabinet = D('Cabinet')->getCabinet($fromCabinetId);
        $toCabinet = D('Cabinet')->getCabinet($toCabinetId);

        $boxModel = D('CabinetBoxModel')->getByModelId($boxModelId);
        $this->ret(0, [
            'deliverId' => $deliverId,
            'boxModelName' => $boxModel['model_name'],
            'toPhone' => $toPhone,
            'toEmail' => $toEmail,
            'from' => [
                'zipcode' => $fromCabinet['zipcode'],
                'cabinetId' => $fromCabinet['cabinet_id'],
                'address' => $fromCabinet['address'],
                'latitude' => $fromCabinet['latitude'],
                'longitude' => $fromCabinet['longitude'],
            ],
            'to' => [
                'zipcode' => $toCabinet['zipcode'],
                'cabinetId' => $toCabinet['cabinet_id'],
                'address' => $toCabinet['address'],
                'latitude' => $toCabinet['latitude'],
                'longitude' => $toCabinet['longitude'],
            ],
            'price' => $price['totalFee'],
        ]);
    }

    /**
     * @api {get} /deliver/payDeliver payDeliver
     * @apiName payDeliver
     * @apiGroup 14-Deliver

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiParam {String}   deliverId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'pay success',                      
            '1' => 'need login',                      
            '2' => 'empty deliverId',                      
            '3' => 'no matched deliver order found',                      
            '4' => 'charge fail, please bind a valid credit card first and try again',                             
     * @apiSuccess {Object} data
     *
     * @apiSampleRequest
     */
    public function payDeliver() {

        $deliverId = I('request.deliverId');
        if(empty($deliverId)) {
            $this->ret(2);
        }
        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) {
            $this->ret(3);
        }
        if(D('ZDeliver')->updateDeliverStatus($deliverId, 'order_success')) {
            $this->ret(0);
        } else {
            $this->ret(4);
        }
    }

    /**
     * @api {get} /deliver/updateDeliver updateDeliver
     * @apiName updateDeliver
     * @apiGroup 14-Deliver

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiParam {String}   deliverId

     * @apiParam {String}   cargoTypeId 货物类别ID，来自getCargoConfig
     * @apiParam {String}   cargoWeight 用户自定义货物重量
     * @apiParam {String}   cargoWorth  用户自定义货物价值
     * @apiParam {String}   toName 收货人name

     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'update deliver success',                      
            '1' => 'need login',                      
     *
     * @apiSampleRequest
     */
    public function updateDeliver() {

        $deliverId     = I('request.deliverId');

        $cargoTypeId   = I('request.cargoTypeId');
        $cargoWeight   = I('request.cargoWeight');
        $cargoWorth    = I('request.cargoWorth');
        $toName        = I('request.toName');

        $deliver = array(
            'cargo_type_id' => $cargoTypeId,
            'cargo_worth' => $cargoWorth,
            'cargo_weight' => $cargoWeight,
            'to_name' => $toName,
        );

        if(!empty(array_filter($deliver))) {
            D('ZDeliver')->updateDeliver($deliverId, $deliver);
        }
        $this->ret(0);
    }

    /**
     * @api {get} /deliver/getDeliverList getDeliverList
     * @apiName getDeliverList
     * @apiGroup 14-Deliver
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
     * @apiSuccess {Object}     data
     *   @apiSuccess {Object[]}   data.list
     *     @apiSuccess {Object}     data.list.deliver
     *       @apiSuccess {String}     data.list.deliver.canCancel 可以取消订单（1可以， 0不可以）
     *       @apiSuccess {String}     data.list.deliver.canPay 可以继续支付（1可以， 0不可以）
     *       @apiSuccess {String}     data.list.deliver.deliverId 订单编号
     *       @apiSuccess {Object}     data.list.deliver.cargo
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoCode 货物码
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoCodeUrl 货物码条码图片url
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoTypeName 货物类别
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoWeight   货物重量
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoWorth    货物估值
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoStatus   货物状态值
     *         @apiSuccess {String}     data.list.deliver.cargo.cargoStatusText 货物状态：Pending, Origin box, In Transit, Destination, Received.
     *         @apiSuccess {String[]}   data.list.deliver.cargo.deliverPhoto  货物照片URL数组
     *       @apiSuccess {Object}     data.list.deliver.from
     *         @apiSuccess {String}     data.list.deliver.from.memberId 发货人ID
     *         @apiSuccess {String}     data.list.deliver.from.cabinetId 发货快递柜ID
     *         @apiSuccess {String}     data.list.deliver.from.address 发货快递柜地址
     *         @apiSuccess {Object}     data.list.deliver.from.box
     *           @apiSuccess {String}     data.list.deliver.from.box.boxId
     *           @apiSuccess {String}     data.list.deliver.from.box.boxModelName
     *       @apiSuccess {Object}     data.list.deliver.to
     *         @apiSuccess {String}     data.list.deliver.to.memberId 收货人ID
     *         @apiSuccess {String}     data.list.deliver.to.cabinetId 收货快递柜ID
     *         @apiSuccess {String}     data.list.deliver.to.address 收货快递柜地址
     *         @apiSuccess {String}     data.list.deliver.to.pickCode 收货开箱码
     *         @apiSuccess {String}     data.list.deliver.to.name 收货人姓名
     *         @apiSuccess {String}     data.list.deliver.to.phone 收货人电话
     *         @apiSuccess {String}     data.list.deliver.to.email 收货人Email
     *         @apiSuccess {Object}     data.list.deliver.to.box
     *           @apiSuccess {String}     data.list.deliver.to.box.boxId
     *           @apiSuccess {String}     data.list.deliver.to.box.boxModelName
     *       @apiSuccess {Object[]}   data.list.deliver.deliverTraceList 订单跟踪信息数组
     *         @apiSuccess {Object}     data.list.deliver.deliverTraceList.track 订单跟踪记录
     *         @apiSuccess {String}     data.list.deliver.deliverTraceList.track.time 跟踪时间
     *         @apiSuccess {String}     data.list.deliver.deliverTraceList.track.text 跟踪状态详情
     *
     * @apiSampleRequest
     */
    public function getDeliverList(){

        $data = [];
        $list = array();
        $deliverList= D('ZDeliver')->getDeliverList([
            'from_member_id'=>$this->_memberId,
            'status' => ['in', [
                C('z_deliver_status_code.order_success'),
                C('z_deliver_status_code.pay_wait'),
                C('z_deliver_status_code.store_success'),
                C('z_deliver_status_code.token_success'),
                C('z_deliver_status_code.fetch_success'),
                C('z_deliver_status_code.deliver_success'),
                C('z_deliver_status_code.pick_success'),
            ]],
        ]);

        foreach($deliverList as $d) {
            $list[] = D('ZDeliver')->formatDeliver($d);
        }

        $data['list'] = $list;
        $this->ret(0, $data);
    }

    /**
     * @api {get} /deliver/getDeliver getDeliver
     * @apiName getDeliver
     * @apiGroup 14-Deliver
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId
     * @apiParam {String}       deliverId

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
     * @apiSuccess {Object}     data
     *   @apiSuccess {Object}     data.deliver
     *     @apiSuccess {String}     data.deliver.deliverId 订单编号
     *       @apiSuccess {String}   data.deliver.canPay 可以继续支付（1可以， 0不可以）
     *       @apiSuccess {String}   data.deliver.canCancel 可以取消订单（1可以， 0不可以）
     *     @apiSuccess {Object}     data.deliver.cargo
     *       @apiSuccess {String}     data.deliver.cargo.cargoCode 货物码
     *       @apiSuccess {String}     data.deliver.cargo.cargoCodeUrl 货物码条码图片url
     *       @apiSuccess {String}     data.deliver.cargo.cargoTypeName 货物类别
     *       @apiSuccess {String}     data.deliver.cargo.cargoWeight   货物重量
     *       @apiSuccess {String}     data.deliver.cargo.cargoWorth    货物估值
     *       @apiSuccess {String}     data.deliver.cargo.cargoStatus   货物状态值
     *       @apiSuccess {String}     data.deliver.cargo.cargoStatusText 货物状态：Pending, Origin box, In Transit, Destination, Received.
     *       @apiSuccess {String[]}   data.deliver.cargo.deliverPhoto  货物照片URL数组
     *     @apiSuccess {Object}     data.deliver.from
     *       @apiSuccess {String}     data.deliver.from.memberId 发货人ID
     *       @apiSuccess {String}     data.deliver.from.cabinetId 发货快递柜ID
     *       @apiSuccess {String}     data.deliver.from.address 发货快递柜地址
     *         @apiSuccess {Object}     data.deliver.from.box
     *           @apiSuccess {String}     data.deliver.from.box.boxId
     *           @apiSuccess {String}     data.deliver.from.box.boxModelName
     *     @apiSuccess {Object}     data.deliver.to
     *       @apiSuccess {String}     data.deliver.to.memberId 收货人ID
     *       @apiSuccess {String}     data.deliver.to.cabinetId 收货快递柜ID
     *       @apiSuccess {String}     data.deliver.to.address 收货快递柜地址
     *       @apiSuccess {String}     data.deliver.to.pickCode 收货开箱码
     *       @apiSuccess {String}     data.deliver.to.name 收货人姓名
     *       @apiSuccess {String}     data.deliver.to.phone 收货人电话
     *       @apiSuccess {String}     data.deliver.to.email 收货人Email
     *         @apiSuccess {Object}     data.deliver.to.box
     *           @apiSuccess {String}     data.deliver.to.box.boxId
     *           @apiSuccess {String}     data.deliver.to.box.boxModelName
     *     @apiSuccess {Object[]}   data.deliver.deliverTraceList 订单跟踪信息数组
     *       @apiSuccess {Object}     data.deliver.deliverTraceList.track 订单跟踪记录
     *       @apiSuccess {String}     data.deliver.deliverTraceList.track.time 跟踪时间
     *       @apiSuccess {String}     data.deliver.deliverTraceList.track.text 跟踪状态详情
     *
     * @apiSampleRequest
     */
    public function getDeliver() {

        $deliverId = I('get.deliverId');
        if(empty($deliverId)) { $this->ret(2);}

        $d = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($d)) { $this->ret(3);}

        $deliver = D('ZDeliver')->formatDeliver($d);

        $this->ret(0, ['deliver' => $deliver]);
    }

    /**
     * @api {get} /deliver/cancelDeliver cancelDeliver
     * @apiName cancelDeliver
     * @apiGroup 14-Deliver
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId
     * @apiParam {String}       deliverId

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
            '0' => 'cancel deliver success',                                                  
            '1' => 'need login',                    
            '2' => 'empty deliver id',                      
            '3' => 'no matched order found',                                                   
            '4' => 'wrong order status',                    
     *
     * @apiSampleRequest
     */
    public function cancelDeliver() {

        $deliverId = I('get.deliverId');
        if(empty($deliverId)) { $this->ret(2);}

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) { $this->ret(3);}

        if(!in_array($deliver['status'], [
            C('z_deliver_status_code.order_success'),
            C('z_deliver_status_code.pay_wait'),
        ])) {
            $this->ret(4);
        }

        D('ZDeliver')->updateDeliverStatus($deliverId, 'order_cancel');

        //release box
        D('CabinetBox')->releaseBox($deliver['from_box_id']);
        D('CabinetBox')->releaseBox($deliver['to_box_id']);

        $this->ret(0);
    }

    /**
     * @api {get} /deliver/getLocationList getLocationList
     * @apiName getLocationList
     * @apiGroup 14-Deliver
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   deliverId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get location list success',              
            '1' => 'need login',              
            '2' => 'empty deliverId',              
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.locationList
     * @apiSuccess {Object}     data.locationList.location
     * @apiSuccess {String}       data.locationList.location.longitude
     * @apiSuccess {String}       data.locationList.location.latitude
     * @apiSuccess {String}       data.locationList.location.time
     *
     * @apiSampleRequest
     */
    public function getLocationList(){

        $deliverId = I('request.deliverId');
        if(empty($deliverId)) {
            $this->ret(2);
        }

        $locationList = D('ZCourierLocation')->getCourierLocationList($deliverId);
        $this->ret(0, ['locaionList' => $locationList]);
    }
}
