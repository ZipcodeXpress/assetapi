<?php
namespace Opr\Controller;
use Think\Controller;

class WalletController extends BaseController {

    /**
     * @api {get} /wallet/getWallet getWallet
     * @apiName getWallet
     * @apiGroup 06-Wallet
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} total                   current total money: 当前总金额
     * @apiSuccess {Number} money                   current money: 当前可用金额
     * @apiSuccess {Number} frozenMoney             current frozen money: 当前冻结金额
     * @apiSuccess {Number} refundMoney             current member max withdraw amount: 当前可提现金额
     * @apiSuccess {Number} ubi                     current ubi amount: 当前优币量
     *
     * @apiSampleRequest
     */
    public function getWallet(){

        $Wallet = D('Wallet');
        $wallet = $Wallet->getWallet($this->_memberId);
        if (empty($wallet)) { $this->ret(2);}

        $res = array(
            'total' => floatval($wallet['money'] + $wallet['frozen_money']),
            'money' => floatval($wallet['money']),
            'frozenMoney' => floatval($wallet['frozen_money']),
            'refundMoney' => min(floatval($wallet['money']), floatval($refundMoney)),
            'ubi' => floatval($wallet['ubi']),
        );
        $this->ret(0, $res);
    }

    /**
     * @api {get} /wallet/recharge recharge
     * @apiName recharge
     * @apiGroup 06-Wallet
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   cardId 信用卡id
     * @apiParam {Number}   amount 充值金额
     *
     * @apiSuccess {String} ret
     * @apiSuccess {String} msg
            '0' => 'recharge success',                  
            '1' => 'need login',                  
            '2' => 'empty cardId or amount',                  
            '3' => 'fail to charge credit card',                  
            '4' => 'wrong cardId, this card is not belong to current member',                  
     * @apiSuccess {Object} data
     * @apiSuccess {String} data.ctr                收据(可以直接作为html展示给用户)
     * @apiSuccess {String} data.amount             金额
     * @apiSuccess {String} data.cardHolderName    持卡人姓名
     * @apiSuccess {String} data.cardType   信用卡类型如Visa
     * @apiSuccess {Number} data.total                   current total money: 当前总金额
     * @apiSuccess {Number} data.money                   current money: 当前可用金额
     * @apiSuccess {Number} data.frozenMoney             current frozen money: 当前冻结金额
     * @apiSuccess {Number} data.refundMoney             current member max withdraw amount: 当前可提现金额
     * @apiSuccess {Number} data.ubi                     current ubi amount: 当前优币量
     *
     * @apiSampleRequest
     */
    public function recharge(){

        $cardId = I('request.cardId');
        $amount = I('request.amount');

        if(empty($cardId) || $amount <= 0) { $this->ret(2);}

        $card = D('CardCredit')->getCardByMemberIdAndCardId($this->_memberId, $cardId);
        if(empty($card)) { $this->ret(4);}

        /** insert statement */
        $stArr = [
            'amount' => -$amount,
            'statement_type' => 'recharge',
            'statement_desc' => 'recharge',
            'channel' => 'credit',
        ];

        $charge = new \Common\Common\Charge();
        $pgResult = $charge->charge($this->_memberId, $amount, $stArr, C('charge_type.recharge'), $cardId);

        if($pgResult['status']) {
            $this->ret($pgResult);
        } else if($pgResult['status'] === 0) {
            $Wallet = D('Wallet');
            $stArr['channel'] = 'account';
            $Wallet->addWallet($this->_memberId, $pgResult['data']['amount'], 0, 0, $stArr);

            if(C('recharge_plus')) {
                $rechargeAmountConfig = C('recharge_amount_config');
                $plusAmount = $rechargeAmountConfig[$pgResult['data']['amount']];
                if($plusAmount > 0) {
                    $stArr['statement_desc'] = 'plus';
                    #$Wallet->addWallet($this->_memberId, $plusAmount, 0, 0, $stArr);
                    $Wallet->addWallet($this->_memberId, 0, 0, $plusAmount * C('ubi_rate'), $stArr);
                }
            }

            $wallet = $Wallet->getWallet($this->_memberId);
            $data = array_merge($pgResult['data'], array(
                'total' => floatval($wallet['money'] + $wallet['frozen_money']),
                'money' => floatval($wallet['money']),
                'frozenMoney' => floatval($wallet['frozen_money']),
                'refundMoney' => min(floatval($wallet['money']), floatval($refundMoney)),
                'ubi' => floatval($wallet['ubi']),
            ));
            $this->ret(0, $data);
        } else {
            $this->ret(3);
        }
    }

    /**
     * @api {get} /wallet/getRechargeAmountConfig getRechargeAmountConfig
     * @apiName getRechargeAmountConfig
     * @apiGroup 06-Wallet
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
     * @apiSuccess {Object[]} data
     * @apiSuccess {Object} data.config
     * @apiSuccess {Number} data.config.amount   充值金额
     * @apiSuccess {Number} data.config.plus     赠送金额
     * @apiSuccess {Number} data.config.plusUbi  赠送u币数量
     *
     * @apiSuccessExample {json} Success-Response:
     {
       "ret": 0,
       "msg": "Success",
       "data": [
         {
           "amount": 10,
           "plus": 5,
           "plusUbi": 500
         },
         {
           "amount": 20,
           "plus": 20,
           "plusUbi": 2000
         },
         {
           "amount": 30,
           "plus": 30,
           "plusUbi": 3000
         },
         {
           "amount": 40,
           "plus": 40,
           "plusUbi": 4000
         },
         {
           "amount": 50,
           "plus": 50,
           "plusUbi": 5000
         },
         {
           "amount": 100,
           "plus": 100,
           "plusUbi": 10000
         }
       ]
     }
     */
    public function getRechargeAmountConfig(){
        $ret = [];
        foreach(C('recharge_amount_config') as $amount => $plus) {
            $ret[] = [
                'amount' => intval($amount),
                'plus' => intval($plus),
                'plusUbi' => intval($plus) * C('ubi_rate'),
            ];
        }
        $this->ret(0, $ret);
    }
}
