<?php
namespace Opr\Controller;
use Think\Controller;

class ConfigController extends BaseController {

    /**
     * @api {get} /config/getConfig getConfig
     * @apiName getConfig
     * @apiGroup 17-Config

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get config success',                      
            '1' => 'need login',                      
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.cargoTypes
     * @apiSuccess {Object} data.cargoTypes.cargoType
     * @apiSuccess {String} data.cargoTypes.cargoType.cargoTypeName eg. flower, food, document...
     * @apiSuccess {String} data.cargoTypes.cargoType.cargoTypeId eg. 10001, 10002, 10003...
     * @apiSuccess {String} data.cargoTypes.cargoType.cargoTypePrice eg. 1.00, 1.50, ...
     * @apiSuccess {Object[]} data.boxModels
     * @apiSuccess {Object} data.boxModels.boxModel
     * @apiSuccess {String} data.boxModels.boxModel.boxModelName 箱子模型名称，eg. large, middle, small...
     * @apiSuccess {String} data.boxModels.boxModel.boxModelId 箱子模型的Id，用于insertDeliver时作为参数
     * @apiSuccess {String} data.boxModels.boxModel.length
     * @apiSuccess {String} data.boxModels.boxModel.width
     * @apiSuccess {String} data.boxModels.boxModel.height
     * @apiSuccess {String} data.boxModels.boxModel.boxModelPrice eg. 1.00, 2.00, ... 不同箱子的价格
     * @apiSuccess {String} data.boxModels.boxModel.img
     * @apiSuccess {String[]} data.holdTimeConfig eg. [24, 48, 72]
     *
     * @apiSampleRequest
     */
    public function getConfig() {
        $cargoTypeArr = D('CargoType')->getCargoTypeConf();

        $boxModelArr = D('CabinetBoxModel')->getBoxModelConf();

        $data = [
            'cargoTypes' => array_values($cargoTypeArr),
            'boxModels' => array_values($boxModelArr),
            'holdTimeConfig' => C('z_store_hold_time'),
        ];

        $this->ret(0, $data);
    }
}
