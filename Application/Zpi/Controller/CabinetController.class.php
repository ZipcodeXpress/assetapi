<?php
namespace Zpi\Controller;
use Think\Controller;
use Common\Common;

class CabinetController extends BaseController {

    /**
     * @api {get} /cabinet/getCabinetList getCabinetList
     * @apiName getCabinetList
     * @apiGroup 12-Cabinet
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId

     * @apiParam {String} [latitude]   纬度
     * @apiParam {String} [longitude]  经度
     * @apiParam {String} [range]      距离范围

     * @apiParam {String} [zipcode]    根据zipcode查找快递柜
     * @apiParam {String} [address]    根据address模糊匹配快递柜address(支持zipcdoe)

     * @apiParam {String} [isDefault]  如果为1，则返回用户绑定的快递柜

     * @apiParam {String} [cabinetId]  获取指定ID的快递柜信息
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Number} msg
            '0' => 'success',                   
            '1' => 'need login',                   
            '2' => 'wrong param',                   
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.defaultAddress                  default adrress: 用户绑定的默认收货地址详情
     * @apiSuccess {String} data.defaultAddress.firstName
     * @apiSuccess {String} data.defaultAddress.lastName
     * @apiSuccess {String} data.defaultAddress.address
     * @apiSuccess {String} data.defaultAddress.city
     * @apiSuccess {String} data.defaultAddress.state
     * @apiSuccess {String} data.defaultAddress.zipcode
     * @apiSuccess {String} data.defaultAddress.longitude
     * @apiSuccess {String} data.defaultAddress.latitude

     * @apiSuccess {Object} data.defaultCabinet                  default cabinet: 用户绑定的的默认快递柜
     * @apiSuccess {String} data.defaultCabinet.cabinetId        cabinetId: 快递柜ID
     * @apiSuccess {String} data.defaultCabinet.img              cabinet image: 快递柜图片
     * @apiSuccess {String} data.defaultCabinet.address          cabinet address: 快递柜地址
     * @apiSuccess {String} data.defaultCabinet.latitude         latitude: 经度
     * @apiSuccess {String} data.defaultCabinet.longitude        longitude: 纬度
     * @apiSuccess {String} data.defaultCabinet.zipcode
     * @apiSuccess {String} data.defaultCabinet.dis              distance: 距离（单位：mile）
     * @apiSuccess {String} data.defaultCabinet.isDefault        is default or not: 是否为默认快递柜， 1:是， 0:不是
     * @apiSuccess {Object[]} data.defaultCabinet.boxModelCount
     *   @apiSuccess {String} data.defaultCabinet.boxModelCount.boxModelName 箱体规格名称：eg. MODEL001
     *   @apiSuccess {String} data.defaultCabinet.boxModelCount.count 箱体个数(根据当前情况返回可用或者全部箱体个数)

     * @apiSuccess {Object[]} data.cabinetList
     * @apiSuccess {Object} data.cabinetList.cabinet
     * @apiSuccess {String} data.cabinetList.cabinet.cabinetId          cabinetId: 快递柜ID
     * @apiSuccess {String} data.cabinetList.cabinet.img                cabinet image: 快递柜图片
     * @apiSuccess {String} data.cabinetList.cabinet.address            cabinet address: 快递柜地址
     * @apiSuccess {String} data.cabinetList.cabinet.latitude           latitude: 经度
     * @apiSuccess {String} data.cabinetList.cabinet.longitude          longitude: 纬度
     * @apiSuccess {String} data.cabinetList.cabinet.zipcode
     * @apiSuccess {String} data.cabinetList.cabinet.dis                distance: 距离（单位：mile）
     * @apiSuccess {String} data.cabinetList.cabinet.isDefault          is default cabinet or not: 是否为默认快递柜， 1:是， 0:不是
     * @apiSuccess {Object[]} data.cabinetList.cabinet.boxModelCount
     *   @apiSuccess {String} data.cabinetList.cabinet.boxModelCount.boxModelName 箱体规格名称：eg. MODEL001
     *   @apiSuccess {String} data.cabinetList.cabinet.boxModelCount.count 箱体个数(根据当前情况返回可用或者全部箱体个数)

     * @apiSuccess {Object[]} data.courierList
     * @apiSuccess {Object} data.courierList.courier
     * @apiSuccess {String} data.courierList.courier.courierId          courierId: 快递员ID
     * @apiSuccess {String} data.courierList.courier.latitude           latitude: 经度
     * @apiSuccess {String} data.courierList.courier.longitude          longitude: 纬度
     * @apiSuccess {String} data.courierList.courier.dis                distance: 距离（单位：mile）
     *
     * @apiSampleRequest
     */
    public function getCabinetList(){

        //根据ID查找
        $cabinetId = I('request.cabinetId');

        //当前登录用户绑定的
        $isDefault = I('request.isDefault');

        //根据zipcode查找
        $zipcode = I('request.zipcode');

        //地图查找
        $latitude = I('request.latitude');
        $longitude = I('request.longitude');
        $range = I('request.range', 0);

        //根据地址查找
        $address = I('request.address');

        if (!empty($this->_memberId)) {
            $cabinetId = D('MemberCabinet')->getFieldByMemberId($this->_memberId, 'cabinet_id');
        } else {
            $cabinetId = 0;
        }

        $Util = new \Common\Common\Util();

        if ($cabinetId) {
            $cabinet = D('Cabinet')->getCabinet($cabinetId);
            if ($cabinet) {
                $dis = $Util->getDistance($latitude, $longitude, $cabinet['latitude'], $cabinet['longitude']); //计算与快递柜的距离
                $data['defaultCabinet'] = array(
                    'cabinetId' => $cabinet['cabinet_id'],
                    'img' => C('CDN_ADDRESS'). $cabinet['cabinet_image'],
                    'address' => $cabinet['address'],
                    'latitude' => $cabinet['latitude'],
                    'longitude' => $cabinet['longitude'],
                    'zipcode' => $cabinet['zipcode'],
                    'dis' => $dis,
                    'isDefault' => 1,
                    'boxModelCount' => array_values(D('CabinetBox')->countByBoxModel($cabinet['cabinet_id'])),
                );
            }
        }

        if($address) {
            if(preg_match('/\d{5}/', $address) === 1) {
                $cabinetList = D('Cabinet')->getCabinetList("zipcode = $address");
            } else {
                $cabinetList = D('Cabinet')->getCabinetList("address like '%".$address."%' or city_name like '%".$address."%'");
            }
        } else {
            $cabinetList = D('Cabinet')->getCabinetList();
        }

        $cbList = array();
        foreach ($cabinetList as $value) {

            $dis = $Util->getDistance($latitude, $longitude, $value['latitude'], $value['longitude']); //计算与快递柜的距离
            if ($range > 0 && ($dis > $range)) { //过滤超过距离限制的快递柜
                continue;
            }

            $cbList[] = array(
                'cabinetId' => $value['cabinet_id'],
                'img' => C('CDN_ADDRESS'). $value['cabinet_image'],
                'address' => $value['address'],
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude'],
                'zipcode' => $value['zipcode'],
                'dis' => $dis,
                'isDefault' => $value['cabinet_id'] == $cabinetId ? 1 : 0,
                'boxModelCount' => array_values(D('CabinetBox')->countByBoxModel($value['cabinet_id'])),

            );
        }

        $crList = array();
        //TODO:
        if($longitude && $latitude) {
            $crList[] = [
                'courierId' => 10001,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'dis' => 123,
            ];
        }

        $cabinetList = $Util->multiArraySort($cbList, 'dis');
        $courierList = $Util->multiArraySort($crList, 'dis');
        $data['cabinetList'] = $cabinetList ? : [];
        $data['courierList'] = $courierList ? : [];
        $this->ret(0, $data);
    }

    /**
     * @api {get} /cabinet/setCabinet setCabinet
     * @apiName setCabinet
     * @apiGroup 12-Cabinet
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {Number} [cabinetId]
     *
     * @apiSuccess {Number}     ret
     * @apiSuccess {String}     msg
            '0' => 'success set member default cabinet',              
            '1' => 'need login',              
            '2' => 'empty cabinetId',              
            '3' => 'no cabinet info found',              
            '4' => 'fail to set cabinet',              
     *
     * @apiSampleRequest
     */
    public function setCabinet(){

        $cabinetId = I('request.cabinetId');
        //if (empty($cabinetId)) { $this->ret(2);}

        if (!empty($cabinetId)) {
            $cabinet = D('Cabinet')->getCabinet($cabinetId);
            if (empty($cabinet)) { $this->ret(3);}

            $ret = D('MemberCabinet')->insertMemberCabinet(array('member_id' => $this->_memberId, 'cabinet_id'=>$cabinetId));

            if(empty($ret)) {
                $this->ret(4);
            }
        }

        if (($cabinetId && $ret) || empty($cabinetId)) {
            $member = D('Member')->getMemberById($this->_memberId);
            if($member['status'] == 2) {
                $upd['status'] = 3;
                D('Member')->updateMemberById($this->_memberId, $upd);
            }
            $this->ret(0);
        }
    }
}
