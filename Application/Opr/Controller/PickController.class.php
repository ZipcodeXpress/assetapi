<?php
namespace Opr\Controller;
use Think\Controller;

class PickController extends BaseController {

    /**
     * @api {get} /pick/getPickList getPickList
     * @apiName getPickList
     * @apiGroup 15-Pick
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
     * @apiSuccess {Object}     data
     * @apiSuccess {Object[]}   data.list
     *   @apiSuccess {Object}     data.list.deliver
     *     @apiSuccess {String}     data.list.deliver.deliverId 订单编号
     *     @apiSuccess {String}     data.list.deliver.canComplain 可以申诉订单（1可以， 0不可以）
     *     @apiSuccess {Object}     data.list.deliver.cargo
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoCode 货物码
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoCodeUrl 货物码条码图片url
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoTypeName 货物类别
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoWeight   货物重量
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoWorth    货物估值
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoStatus   货物状态值
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoStatusText 货物状态：Pending, Origin box, In Transit, Destination, Received.
     *       @apiSuccess {String[]}   data.list.deliver.cargo.deliverPhoto  货物照片URL数组
     *     @apiSuccess {Object}     data.list.deliver.from
     *       @apiSuccess {String}     data.list.deliver.from.memberId 发货人ID
     *       @apiSuccess {String}     data.list.deliver.from.cabinetId 发货快递柜ID
     *       @apiSuccess {String}     data.list.deliver.from.address 发货快递柜地址
     *       @apiSuccess {String}     data.list.deliver.from.name 发件人姓名
     *         @apiSuccess {Object}     data.list.deliver.from.box
     *           @apiSuccess {String}     data.list.deliver.from.box.boxId
     *           @apiSuccess {String}     data.list.deliver.from.box.boxModelName
     *     @apiSuccess {Object}     data.list.deliver.to
     *       @apiSuccess {String}     data.list.deliver.to.memberId 收货人ID
     *       @apiSuccess {String}     data.list.deliver.to.cabinetId 收货快递柜ID
     *       @apiSuccess {String}     data.list.deliver.to.address 收货快递柜地址
     *       @apiSuccess {String}     data.list.deliver.to.pickCode 收货开箱码
     *       @apiSuccess {String}     data.list.deliver.to.name 收货人姓名
     *       @apiSuccess {String}     data.list.deliver.to.phone 收货人电话
     *       @apiSuccess {String}     data.list.deliver.to.email 收货人Email
     *         @apiSuccess {Object}     data.list.deliver.to.box
     *           @apiSuccess {String}     data.list.deliver.to.box.boxId
     *           @apiSuccess {String}     data.list.deliver.to.box.boxModelName
     *     @apiSuccess {Object[]}   data.list.deliver.deliverTraceList 订单跟踪信息数组
     *       @apiSuccess {Object}     data.list.deliver.deliverTraceList.track 订单跟踪记录
     *       @apiSuccess {String}     data.list.deliver.deliverTraceList.track.time 跟踪时间
     *       @apiSuccess {String}     data.list.deliver.deliverTraceList.track.text 跟踪状态详情
     *
     * @apiSampleRequest
     */
    public function getPickList(){

        $data = [];
        $list = array();
        $deliverList= D('ZDeliver')->getDeliverList([
            'to_member_id'=>$this->_memberId,
            'status' => ['in', [
                C('z_deliver_status_code.deliver_success'),
            ]],
        ]);

        foreach($deliverList as $d) {
            $list[] = D('ZDeliver')->formatDeliver($d, 'memberGetPickList');
        }

        $data['list'] = $list;
        $this->ret(0, $data);
    }

    /**
     * @api {get} /pick/complainPick complainPick
     * @apiName complainPick
     * @apiGroup 15-Pick
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
    public function complainPick() {

        $deliverId = I('get.deliverId');
        if(empty($deliverId)) { $this->ret(2);}

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) { $this->ret(3);}

        $content = I('get.content');
        if(empty($content)) { $this->ret(4);}

        if($deliver['status'] !== C('z_deliver_status_code.pick_success')) {
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
            'order_type' => 'ziplocker_pick',
        ])) {
            D('ZDeliver')->updateDeliverStatus($deliverId, 'pick_complain');
        }

        $this->ret(0);
    }
}
