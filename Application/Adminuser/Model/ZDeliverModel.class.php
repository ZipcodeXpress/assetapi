<?php
namespace Adminuser\Model;
use Think\Model;

class ZDeliverModel extends Model{

    public function getDeliver($wh=[]){
        return $this->where($wh)->find();
    }

    public function getDeliverList($wh = array()){
        return $this->where($wh)->order('create_time desc')->select();
    }

    public function countByFromCabinetId($fromCabinetId, $wh=[]){
        $wh = array_merge($wh, [
            'from_cabinet_id' => $fromCabinetId,
        ]);
        return $this->where($wh)->count();
    }

    public function getCargoStatus($orderStatus) {
        $cargoStatusTextConf = C('z_cargo_status_text');

        if($orderStatus < C('z_deliver_status_code.store_success')) {
            $cargoStatus = C('z_cargo_status.pending');
        } else if($orderStatus < C('z_deliver_status_code.fetch_success')) {
            $cargoStatus = C('z_cargo_status.origin_box');
        } else if($orderStatus < C('z_deliver_status_code.deliver_success')) {
            $cargoStatus = C('z_cargo_status.in_transit');
        } else if($orderStatus < C('z_deliver_status_code.pick_success')) {
            $cargoStatus = C('z_cargo_status.destination');
        //} else if($orderStatus == C('z_deliver_status_code.pick_success')) {
        } else {
            $cargoStatus = C('z_cargo_status.received');
        }

        return [
            'cargoStatus' => $cargoStatus,
            'cargoStatusText' => $cargoStatusTextConf[$cargoStatus],
        ];
    }

    /**
        memberGetDeliverList
        memberGetPickList
        courierGetOrderList
        courierGetDeliverList
        courierGetDeliverListFromCabinet
        courierGetDeliverFrom
    */
    public function formatDeliver($d, $format='memberGetDeliverList', $extra=[], $lat=null, $lng=null) {

        $cargoTypeConf = D('CargoType')->getCargoTypeConf();

        $fmToCabinetArr = D('Cabinet')->getCabinetArr(['cabinet_id' => ['in', [
            $d['from_cabinet_id'],
            $d['to_cabinet_id'],
        ]]]);

        $fromMember = D('MemberView')->getMemberView($d['from_member_id']);
        $fromMemberName = $fromMember['first_name'].' '.$fromMember['last_name'];
        $fromMemberPhone = $fromMember['phone'];

        $ret =  [
            'deliverId' => $d['deliver_id'],
            'cargo' => [
                'cargoCode' => $d['cargo_code'],
                'cargoCodeUrl' => C('WWW_ADDRESS').U('/zpi/barcode/show', ['text'=>$d['cargo_code']]),
                'cargoTypeName' => $cargoTypeConf[$d['cargo_type_id']]['cargoTypeName'] ? : '--',
                'cargoWeight' => $d['cargo_weight'] ? : 0,
                'cargoWorth' => $d['cargo_worth'] ? : 0,
                'deliverPhoto' => isset($d['deliver_photo_group_id']) ? D('PhotoGroup')->getPhotoGroup($d['deliver_photo_group_id']) : [],
            ],
            'from' => [
                'memberId' => $d['from_member_id'],
                'cabinetId' => $d['from_cabinet_id'],
                'address' => $fmToCabinetArr[$d['from_cabinet_id']]['address'],
                'name' => D('MemberProfile')->getMemberName($d['from_member_id']),
            ],
            'to' => [
                'memberId' => $d['to_member_id'],
                'cabinetId' => $d['to_cabinet_id'],
                'address' => $fmToCabinetArr[$d['to_cabinet_id']]['address'],
                'name' => $d['to_name'],
                'phone' => $d['to_phone'],
                'email' => $d['to_email'],
            ],
            'deliverTraceList' => D('ZDeliverTrace')->getDeliverTraceList(['deliver_id'=>$d['deliver_id']]),
        ];

        switch($format) {
            case 'memberGetDeliverList':
                $fromBox = D('CabinetBox')->getBodyBox($d['from_box_id']);
                if(empty($d['to_box_id'])) {
                    $toBox = D('CabinetBox')->getBodyBox($d['from_box_id']);
                } else {
                    $toBox = D('CabinetBox')->getBodyBox($d['to_box_id']);
                }
                $ret['cargo'] = array_merge($ret['cargo'], $this->getCargoStatus($d['status']));
                $ret['from']['box'] = [
                    'boxId' => $fromBox['box_id'],
                    'boxModelName' => $fromBox['box_model_name'],
                ];
                $ret['to']['box'] = [
                    'boxId' => $toBox['box_id'],
                    'boxModelName' => $toBox['box_model_name'],
                ];
                $ret['to']['pickCode'] = $d['pick_code'];

                $ret['canPay'] = $d['status'] == C('z_deliver_status_code.pay_wait') ? 1 : 0;
                $ret['canCancel'] = $d['status'] == C('z_deliver_status_code.order_success') ? 1 : 0;
                break;
            case 'memberGetPickList':
                $fromBox = D('CabinetBox')->getBodyBox($d['from_box_id']);
                if(empty($d['to_box_id'])) {
                    $toBox = D('CabinetBox')->getBodyBox($d['from_box_id']);
                } else {
                    $toBox = D('CabinetBox')->getBodyBox($d['to_box_id']);
                }
                $ret['cargo'] = array_merge($ret['cargo'], $this->getCargoStatus($d['status']));
                $ret['from']['box'] = [
                    'boxId' => $fromBox['box_id'],
                    'boxModelName' => $fromBox['box_model_name'],
                ];
                $ret['to']['box'] = [
                    'boxId' => $toBox['box_id'],
                    'boxModelName' => $toBox['box_model_name'],
                ];
                $ret['to']['pickCode'] = $d['pick_code'];

                $ret['canComplain'] = $d['status'] == C('z_deliver_status_code.pick_success') ? 1 : 0;
                break;
            case 'courierGetOrderList':
                $ret = array_merge($ret, [
                    'takeTime' => date('Y-m-d H:i:s', $extra['create_time']),
                    'pickTime' => $extra['fetch_time'] ? date('Y-m-d H:i:s', $extra['fetch_time']) : '',
                    'putTime' => $extra['deliver_time'] ? date('Y-m-d H:i:s', $extra['deliver_time']) : '',
                    'putDist' => floatval($d['dist']),
                    'fee' => $extra['fee_total'],
                ]);
                $ret['to'] = array_merge($ret['to'], [
                    'longitude' => $fmToCabinetArr[$d['to_cabinet_id']]['longitude'],
                    'latitude' => $fmToCabinetArr[$d['to_cabinet_id']]['latitude'],
                ]);
                $ret['from'] = array_merge($ret['from'], [
                    'name' => $fromMemberName,
                    'phone' => $fromMemberPhone,
                    'longitude' => $fmToCabinetArr[$d['from_cabinet_id']]['longitude'],
                    'latitude' => $fmToCabinetArr[$d['from_cabinet_id']]['latitude'],
                ]);

                $ret['canCancel'] = $d['status'] == C('z_deliver_status_code.token_success') ? 1 : 0;
                $ret['canComplain'] = $d['status'] == C('z_deliver_status_code.fetch_success') ? 1 : 0;
                $ret['orderId'] = $extra['order_id'];
                break;
            case 'courierGetDeliverList':
                $Util = new \Common\Common\Util();
                $ret = array_merge($ret, [
                    'pickDist' => $Util->getDistance($lat, $lng, $fmToCabinetArr[$d['from_cabinet_id']]['latitude'], $fmToCabinetArr[$d['from_cabinet_id']]['longitude']),
                    'putDist' => floatval($d['dist']),
                    'fee' => C('z_courier_bonus_rate') * $d['fee_total'],
                    'createTime' => date('Y-m-d H:i:s', $d['create_time']),
                ]);
                $ret['from'] = array_merge($ret['from'], [
                    'name' => $fromMemberName,
                    'phone' => '***********',
                ]);
                $ret['to']['phone'] = '***********';
                break;
            case 'courierGetDeliverListFromCabinet':
                $Util = new \Common\Common\Util();
                $ret = array_merge($ret, [
                    'pickDist' => $Util->getDistance($lat, $lng, $fmToCabinetArr[$d['from_cabinet_id']]['latitude'], $fmToCabinetArr[$d['from_cabinet_id']]['longitude']),
                    'putDist' => floatval($d['dist']),
                    'fee' => C('z_courier_bonus_rate') * $d['fee_total'],
                    'createTime' => date('Y-m-d H:i:s', $d['create_time']),
                ]);
                unset($ret['from']);
                break;
            case 'courierGetDeliverFrom':
                //only return $from
                $ret = array_merge($ret['from'], [
                    'name' => $fromMemberName,
                    'phone' => $fromMemberPhone,
                ]);
                break;
            default:
        }

        return $ret;
    }

    public function calDeliverPrice($arr) {

        #$cargoTypeId   = $arr['cargoTypeId'];
        #$cargoWeight   = $arr['cargoWeight'];
        #$cargoWorth    = $arr['cargoWorth'];
        $boxModelId    = $arr['boxModelId'];
        $fromCabinetId = $arr['fromCabinetId'];
        $toCabinetId   = $arr['toCabinetId'];

        #if(empty($cargoTypeId)) { return false;}
        #if(empty($cargoWeight)) { return false;}
        #if(empty($cargoWorth))  { return false;}
        if(empty($boxModelId))    { return false;}
        if(empty($fromCabinetId)) { return false;}
        if(empty($toCabinetId))   { return false;}

        $baseFee = 0;

        $tipFee = 0;

        if($cargoTypeId) {
            $cargoTypeConf = D('CargoType')->getCargoTypeConf();
            $cargoTypeFee   = $cargoTypeConf[$cargoTypeId]['cargoTypePrice'];
        }
        if($cargoWeight) {
            $cargoWeightFee = $cargoWeight * C('z_price_config.cargo_weight');
        }
        if($cargoWorth) {
            $cargoWorthFee  = $cargoWorth * C('z_price_config.cargo_worth');
        }

        $boxModelConf = D('CabinetBoxModel')->getBoxModelConf();
        $boxFee = $boxModelConf[$boxModelId]['boxModelPrice'];

        if($fromCabinetId == $toCabinetId) {
            $distFee = 0;
        } else {
            $deliverDist = D('Cabinet')->getDistance($fromCabinetId, $toCabinetId);
            $distFee = $deliverDist * C('z_price_config.dist_price_rate');
            $boxFee = $boxFee*2;
        }

        $feeArr =  [
            'baseFee'          => $baseFee,
            'tipFee'           => $tipFee,
            'cargoTypeFee'     => $cargoTypeFee ? : 0,
            'cargoWeightFee'   => $cargoWeightFee ? : 0,
            'cargoWorthFee'    => $cargoWorthFee ? : 0,
            'boxFee'           => $boxFee,
            'distFee'          => $distFee,
        ];
        $totalFee = array_sum($feeArr);
        return array_merge($feeArr, ['totalFee' => $totalFee]);
    }

    /** update/insert data */

    public function updateDeliver($deliverId, $deliver){

        return $this->where(['deliver_id'=>$deliverId])->data($deliver)->save();
    }

    public function updateDeliverStatus($deliverId, $status, $extraArr=[]){

        /*** chargeDeliver **/
        if(in_array($status, [
            'order_cancel',
            'order_success',
            'store_success',
            'token_cancel',
            'fetch_timeout',
            'deliver_success',
            'pick_success',
        ])) {
            $Charge = new \Common\Common\Charge();
            if($Charge->chargeDeliver($deliverId, $status) === false) {
                return false;
            }
        }

        $statusCodeConf = C('z_deliver_status_code');
        $statusCode = $statusCodeConf[$status];
        $statusTextConf = C('z_deliver_status_text');
        $trace = $statusTextConf[$statusCode];

        $extraArr = $extraArr ? : [];//bugfix: $extraArr must not be null before array_merge
        $data = array_merge([
            'status' => $statusCode,
            'update_time' => time()
        ], $extraArr);

        switch($status) {
            case 'order_success':
                $data['order_time'] = time();
                break;
            case 'store_success':
                $data['store_time'] = time();
                break;
            case 'token_success':
                $data['token_time'] = time();
                break;
            case 'token_cancel':
                $data['status'] = C('z_deliver_status_code.store_success');
                $data['token_time'] = ['exp', 'null'];
                $data['courier_id'] = ['exp', 'null'];
                break;
            case 'fetch_timeout':
                $data['status'] = C('z_deliver_status_code.order_success');
                $data['token_time'] = ['exp', 'null'];
                $data['courier_id'] = ['exp', 'null'];
                break;
            case 'fetch_success':
                $data['fetch_time'] = time();
                break;
            case 'deliver_success':
                $data['deliver_time'] = time();
                break;
            case 'pick_success':
                $data['pick_time'] = time();
                break;
        }

        $ret1 = $this->data($data)->where([
            'deliver_id' => $deliverId
        ])->save();

        $ret2 = D('ZDeliverTrace')->insertDeliverTrace($deliverId, $trace);

        return $ret1 && $ret2;
    }

    public function insertDeliver($data){
        if ($this->create($data)) {
            $ret1 = $deliverId = $this->add();

            $statusTextConf = C('z_deliver_status_text');
            $trace = $statusTextConf[$data['status']];
            $ret2 = D('ZDeliverTrace')->insertDeliverTrace($deliverId, $trace);

            return $deliverId;
        } else {
            return false;
        }
    }
	    public function insertMember($data){
       if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function getBodyList($where = array()){
        return $this->where($where)->select();
    }
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('deliver_id')->select();
    }
	
	public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
	
    public function getCabinetList($where = array()){
        return $this->where($where)->select();
    }
}
