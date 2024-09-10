<?php
namespace Opr\Controller;
use Think\Controller;

class CardCreditController extends BaseController {

    /**
     * @api {get} /CardCredit/getCardCreditList getCardCreditList
     * @apiName getCardCreditList
     * @apiGroup 04-CardCredit
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number}   ret
     * @apiSuccess {String}   msg
     * @apiSuccess {Object}   data
     * @apiSuccess {Object[]} data.cardList
     * @apiSuccess {Object}   data.cardList.card
     * @apiSuccess {String}   data.cardList.card.cardId
     * @apiSuccess {String}   data.cardList.card.cardLast4 卡号后4位,展示请用 ****1234
     * @apiSuccess {String}   data.cardList.card.cardType
     * @apiSuccess {String}   data.cardList.card.cardHolderName
     * @apiSuccess {String}   data.cardList.card.createTime
     * @apiSuccess {String}   data.cardList.card.updateTime
     * @apiSuccess {String}   data.cardList.card.isDefault 是否是默认卡片
     * @apiSuccess {String}   data.cardList.card.status 卡片状态，0可用，1不可用
     * @apiSuccess {String}   data.cardList.card.statusMsg 卡片状态详情
     *
     * @apiSampleRequest
     */
    public function getCardCreditList(){

        $CardCredit = D('CardCredit');
        $cardList = $CardCredit->getCardListByMemberId($this->_memberId);
        $data = [];
        foreach($cardList as $key => $value) {
            $data[] = [
                'cardId' => $value['card_id'],
                'cardLast4' => $value['card_last4'],
                'cardType' => $value['card_type'],
                'cardHolderName' => $value['card_holder_name'],
                'createTime' => $value['create_time'],
                'updateTime' => $value['update_time'],
                'isDefault' => $value['is_default'],
                'status' => $value['status'],
                'statusMsg' => $value['status_msg'],
            ];
        }
        $res['cardList'] = $data;
        $this->ret(0, $res);
    }

    /**
     * @api {post} /CardCredit/insertCardCredit insertCardCredit
     * @apiName insertCardCredit
     * @apiGroup 04-CardCredit
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} cardNum 16位卡号 格式：4111111111111111
     * @apiParam {String} cardHolderName 持卡人姓名
     * @apiParam {String} expDate 4位数字过期年+月，格式：1020(2020年10月)
     * @apiParam {String} cvv2 3位数字CVV，格式：123
     * @apiParam {String} zipcode 5位数字，格式：12345
     * @apiParam {String} [isDefault] 是否同时设置为默认卡片
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'update profile success',            
            '1' => 'need login',            
            '2' => 'invalid card number',            
            '3' => 'empty card hoder name',            
            '4' => 'empty zipcode',            
            '5' => 'empty expiry date or invalid expiry date (format: 0620)',            
            '6' => 'empty cvv2',            
            '7' => 'the credit card has been binded by other member',            
            '8' => 'insert credit card fail',            
     * @apiSuccess {Object} data
     *
     * @apiSampleRequest
     */
    public function insertCardCredit(){

        if(empty($this->_memberId)) { $this->ret(1);}

        $cardNum        = I('request.cardNum');
        $cardHolderName = I('request.cardHolderName');
        $zipcode        = I('request.zipcode');
        $expDate        = I('request.expDate');
        $cvv2           = I('request.cvv2');
        $isDefault      = I('request.isDefault');

        if (empty($cardNum       )) $this->ret(2);
        if (strlen($cardNum) > 16) $this->ret(2);
        if (empty($cardHolderName)) $this->ret(3);
        #if (empty($zipcode       )) $this->ret(4);
        if (empty($expDate       ) || strlen($expDate) !== 4) $this->ret(5);
        if (empty($cvv2          )) $this->ret(6);

        $profile = D('MemberProfile')->getProfile($this->_memberId);
        //if(!($profile && $profile['zipcode'] && $profile['addressline1'])) { $this->ret(4);}
        $zipcode = $zipcode ? : $profile['zipcode'];
        if(empty($zipcode)) { $this->ret(4);}

        $card = [
            'card_code' => $cardNum,
            'exp_date' => $expDate,
            'cvv2' => $cvv2,
            'card_holder_name' => $cardHolderName,
            'zipcode' => $zipcode,
        ];

        $CardCredit = D('CardCredit');
        $cardCredit = $CardCredit->getCardByCode($cardNum);
        if($cardCredit && $cardCredit['member_id']) {
            $this->ret(7);
        } else {

            $Charge = new \Common\Common\Charge();
            if($profile['profile_id']) {
                $pgResult = $Charge->createPaymentProfile($this->_memberId, $card, $profile['profile_id']);
            } else {
                $pgResult = $Charge->createProfile($this->_memberId, $card);
                if($pgResult['status'] == 0) {
                    D('MemberProfile')->updateProfile($this->_memberId, ['profile_id'=>$pgResult['profileId']]);
                }
            }

            if($pgResult['status']) {
                $this->ret(9, NULL, $pgResult['msg']);
            }

            $cardId = $CardCredit->insertCard([
                'member_id' => $this->_memberId,
                'card_code' => $cardNum,
                'profile_id' => $profile['profile_id'] ? : $pgResult['profileId'],
                'payment_profile_id' => $pgResult['paymentProfileId'],
                'card_holder_name' => $cardHolderName,
                'zipcode' => $zipcode,
                'exp_date' => $expDate,
                'cvv2' => $cvv2,
                'is_default' => $isDefault,
            ]);
        }
        if(empty($cardId)) {
            $this->ret(8);
        }
        D('Member')->updateMemberById($this->_memberId, [
            'status' => 0,
            'has_credit_card' => 1,
        ]);

        /** AutoSupplement will check member wallet,
         *  if insufficient, auto charge with new added credit card.
         */
        //$Wallet = D('Wallet');
        //$Wallet->autoSupplement($this->_memberId, $cardId);

        $this->ret(0);
    }

    /**
     * @api {post} /CardCredit/setDefault setDefault
     * @apiName setDefault
     * @apiGroup 04-CardCredit
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} cardId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',            
            '1' => 'need login',            
            '2' => 'wrong param',            
            '3' => 'fail to set as default card',            
     *
     * @apiSampleRequest
     */
    public function setDefault(){

        $cardId = I('request.cardId');

        if (empty($cardId)) $this->ret(2);

        $CardCredit = D('CardCredit');
        $ret = $CardCredit->setDefault($this->_memberId, $cardId);
        if($ret) {
            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }

    /**
     * @api {post} /CardCredit/delete delete
     * @apiName delete
     * @apiGroup 04-CardCredit
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} cardId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',            
            '1' => 'need login',            
            '2' => 'wrong param',            
            '3' => 'fail to delete card',            
            '4' => 'card not exist',                    
     *
     * @apiSampleRequest
     */
    public function delete(){

        $cardId = I('request.cardId');
        $CardCredit = D('CardCredit');

        if (empty($cardId)) $this->ret(2);

        /** 经过扣款失败，已过期的信用卡 */
        #if($card['status'] == 1) {
        #    #delete directly, unconditionally
        #}

        /** 删除默认信用卡 */
        $card = $CardCredit->getCardById($cardId);
        if(empty($card)) $this->ret(4);

        $needCheck = false;
        if($card['status'] == 0) {
            if($card['is_default'] == 1) {
                $needCheck = true;
            } else {
                $defaultCard = $CardCredit->getDefaultCard($this->_memberId, 'default', true);
                if($defaultCard && $defaultCard['card_id'] != $card['card_id']) {
                    #has other default card, delete directly, unconditionally
                    $needCheck = false;
                } else {
                    //bug, should not happen
                    $needCheck = true;
                }
            }
        }

        if($needCheck) {
            //TODO: check
        }

        $ret = $CardCredit->deleteCard($this->_memberId, $cardId);
        if($ret) {
            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }
}
