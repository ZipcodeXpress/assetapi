<?php
namespace Opr\Controller;
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
    /**
     * @api {get} /state/getTRANSACTIONDetail getTRANSACTIONDetail
     * @apiName getTRANSACTIONDetail
     * @apiGroup 18-State
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   startime
     * @apiParam {String}   endtime
 
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param'
     * @apiSuccess {Object} data
     * @apiSuccess {String}     data.money 冲值金额
     * @apiSuccess {String}     data.datetime 冲值时间
     *
     * @apiSampleRequest
     */
    public function getTRANSACTIONDetail() {
         $startime = I('request.startime');
         $endtime = I('request.endtime');
         if ($startime>$endtime) {$this->ret(2);}
         $smst = D('ProductRental')->getStatementList($this->_memberId,1,$startime,$endtime);
         if(empty($smst)) { $this->ret(2);}
		 
         $this->ret(0, ['data'=>$smst]);

    }
	    /**
     * @api {get} /state/getPossessDetail getPossessDetail
     * @apiName getPossessDetail
     * @apiGroup 18-State
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId

     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'No Possessions'
     * @apiSuccess {Object} data
     * @apiSuccess {String}     data.money 冲值金额
     * @apiSuccess {String}     data.datetime 冲值时间
     *
     * @apiSampleRequest
     */
    public function getPossessDetail() {
 		$wh=
		[
		  't.member_id' => $this->_memberId,
		  't.product_status_code' => '3',
		];
        $smst = D('ProductInventory')->getStatementListN($wh);
        if(empty($smst)) { $this->ret(2);}
		 
        $this->ret(0, ['data'=>$smst]);

    }
	    /**
     * @api {get} /state/getRESERVEDetail getRESERVEDetail
     * @apiName getRESERVEDetail
     * @apiGroup 18-State
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param'
     * @apiSuccess {Object} data
     * @apiSuccess {String}     data.money 冲值金额
     * @apiSuccess {String}     data.datetime 冲值时间
     *
     * @apiSampleRequest
     */
    public function getRESERVEDetail() {
 		$wh=
		[
		  't.member_id' => $this->_memberId,
		  't.rental_status_code' => '2',
		];
         if ($startime>$endtime) {$this->ret(2);}
         $smst = D('ProductRental')->getStatementListNN($wh);
         if(empty($smst)) { $this->ret(2);}
		 
         $this->ret(0, ['data'=>$smst]);

    }
}
