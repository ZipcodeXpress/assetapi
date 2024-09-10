<?php
namespace Zpi\Controller;
use Think\Controller;

class StateController extends BaseController {

    /**
     * @api {get} /state/getStateList getStateList
     * @apiName getStateList
     * @apiGroup 18-State
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',                   
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.stateList
     * @apiSuccess {Object}     data.stateList.state
     * @apiSuccess {String}     data.stateList.state.state 州名称
     * @apiSuccess {String}     data.stateList.state.stateCode 代码
     * @apiSuccess {String}     data.stateList.state.taxRate 税率
     *
     * @apiSampleRequest
     */
    public function getStateList() {

        $list = D('State')->getStateList();
        $this->ret(0, ['stateList'=>$list]);
    }
}
