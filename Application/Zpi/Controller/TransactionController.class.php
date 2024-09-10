<?php
namespace Zpi\Controller;
use Think\Controller;

class TransactionController extends BaseController {

    /**
     * @api {get} /Transaction/getTransactionList getTransactionList
     * @apiName getTransactionList
     * @apiGroup 13-Transaction
     *
     * @apiParam {String}       _accessToken
     * @apiParam {String}       _memberId
     * @apiParam {String}       type (deliver/pick)

     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
     * @apiSuccess {Object}     data
     * @apiSuccess {Object[]}   data.list
     *   @apiSuccess {Object}     data.list.deliver
     *     @apiSuccess {String}     data.list.deliver.deliverId 订单编号
     *     @apiSuccess {String}     data.list.deliver.cargo
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoCode 货物码
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoStatus   货物状态值
     *       @apiSuccess {String}     data.list.deliver.cargo.cargoStatusText 货物状态：Pending, Origin box, In Transit, Destination, Received.
     *     @apiSuccess {Object}     data.list.deliver.from
     *       @apiSuccess {String}     data.list.deliver.from.zipcode
     *       @apiSuccess {String}     data.list.deliver.from.time
     *     @apiSuccess {Object}     data.list.deliver.to
     *       @apiSuccess {String}     data.list.deliver.to.zipcode
     *       @apiSuccess {String}     data.list.deliver.to.time
     *       @apiSuccess {String}     data.list.deliver.to.pickCode
     *
     * @apiSampleRequest
     */
    public function getTransactionList(){

        $data = [];
        $list = array();

        if(I('request.type') == 'pick') {
            $deliverList= D('ZDeliver')->getDeliverList(['to_member_id'=>$this->_memberId]);
        } else {
            $deliverList= D('ZDeliver')->getDeliverList(['from_member_id'=>$this->_memberId]);
        }

        foreach($deliverList as $d) {
            $list[] = [
                'deliverId' => $d['deliver_id'],
                'cargo' => array_merge(
                    ['cargoCode' => $d['cargo_code']],
                    D('ZDeliver')->getCargoStatus($d['status'])
                ),
                'from' => [
                    'zipcode' => $d['from_zipcode'],
                    'time' => $d['store_time'] ? date('Y-m-d H:i:s', $d['store_time']) : '--',
                ],
                'to' => [
                    'zipcode' => $d['to_zipcode'] ? : $d['from_zipcode'],
                    'time' => $d['deliver_time'] ? date('Y-m-d H:i:s', $d['deliver_time']) : '--',
                    'pickCode' => $d['pick_code'],
                ],
            ];
        }

        $data['list'] = $list;
        $this->ret(0, $data);
    }
}
