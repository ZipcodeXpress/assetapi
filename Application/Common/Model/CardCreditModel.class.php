<?php
namespace Common\Model;
use Think\Model;

class CardCreditModel extends Model{

    protected $_scope = [
        'default' => ['field' => 'card_id, member_id, card_last4, card_type, card_holder_name, create_time, update_time, is_default, status, status_msg'],
        'verify' => ['field' => 'card_id, member_id, card_last4, card_type, card_holder_name, create_time, update_time, profile_id, payment_profile_id, card_code, card_token, exp_date, is_default, status, status_msg'],
        'pay' => ['field' => 'card_id, member_id, card_last4, card_type, card_holder_name, create_time, update_time, card_token, exp_date, is_default, status, status_msg'],
        'profile' => ['field' => 'card_id, member_id, card_last4, card_holder_name, create_time, update_time, profile_id, payment_profile_id, exp_date, is_default, status, status_msg'],
    ];

    public function getCardList(){
        return $this->scope()->select();
    }

    public function getCardListByMemberId($memberId){
        return $this->where("member_id=%d", $memberId)->scope()->select();
    }

    public function getCardById($cardId){
        return $this->where("card_id=%d", $cardId)->find();
    }

    public function getCardByCode($cardCode){
        return $this->where(array('card_code'=>$cardCode))->find();
    }

    public function getByAccessCode($cardCode){
        return $this->where(array('access_code'=>$cardCode))->find();
    }

    public function getDefaultCard($memberId, $scope='default', $forceGetDefault=false) {
        /** find is_default card at first */
        $where = [
            'member_id' => $memberId,
            'is_default' => 1,
            'status' => 0,
        ];
        $card = $this->where($where)->scope($scope)->order('update_time desc, create_time desc')->find();
        /** if forceGetDefault, no matter find or not, return it */
        if($forceGetDefault) {
            return $card;
        }
        /** if not forceGetDefault && no default card found, then find a new added valid card, set as default card && return it */
        if(empty($card)) {
            unset($where['is_default']);
            $card = $this->where($where)->scope($scope)->order('update_time desc, create_time desc')->find();
            $this->setDefault($memberId, $card['card_id']);
        }
        return $card;
    }

    public function checkCard($memberId){
        return empty($this->getDefaultCard($memberId)) ? false : true;
    }

    public function getCardByMemberIdAndCardId($memberId, $cardId, $scope='default'){
        return $this->where("member_id=%d AND card_id=%d", $memberId, $cardId)->scope($scope)->find();
    }

    public function setDefault($memberId, $cardId) {
        /**
            Kiosk/Api/identifyCard:           when old card & old member, set default
            Api/CardCredit/setDefault:        when user want to set default 
            Model/CardCredit/getDefaultCard:  when default card not set && has valid card
         */
        $this->where(['member_id'=>$memberId, 'card_id'=>['neq', $cardId]])->data(['is_default'=>0])->save();
        return $this->where(['member_id'=>$memberId, 'card_id'=>$cardId])->data(['is_default'=>1, 'update_time'=>time()])->save();
    }

    public function deleteCard($memberId, $cardId) {
        return $this->where(['member_id'=>$memberId, 'card_id'=>$cardId])->delete();
    }

    public function updateCardById($cardId, $data){
        //this interface won't allow set default, but will set default automatically
        unset($data['is_default']);

        /**
            Kiosk/Api/bindAccount: update member_id
            Common/Charge/charge: update status, status_msg
            Common/Charge/verify: update status, status_msg, card_token
         */

        if($data['member_id']) {
            if(empty($this->getDefaultCard($data['member_id'], $scope='default', $forceGetDefault=true))) {
                $data['is_default'] = 1;
            }
        }
        //for invalid card, set is_default=0
        if($data['status'] == 1) {
            $data['is_default'] = 0;
        }
        $data['update_time'] = time();
        if($this->data($data)->where('card_id=%d', $cardId)->save()) {
            return $cardId;
        } else {
            return false;
        }
    }

    public function insertCard($data){
        //this interface won't allow set default, but will set default automatically
        unset($data['is_default']);

        /**
            Api/CardCredit/insertCardCredit:    insert card data
            Kiosk/Api/verifyCreditCard:         insert card data, without member_id, cvv2
         */

        if($data['card_code']) {
            $data['card_last4'] = substr($data['card_code'], -4);
            $data['card_md5'] = md5($data['card_code']);
            if($data['member_id']) {
                if(empty($this->getDefaultCard($data['member_id'], $scope='default', $forceGetDefault=true))) {
                    //if no default card, this must be a default card
                    $data['is_default'] = 1;
                }
            }

            $card = $this->getCardByCode($data['card_code']);
            if($card) {
                //when card is inserted in web api, member_id is essential
                //when card is inserted in kiosk api, member_id is empty
                if($data['member_id']) {
                    //for Api/CardCredit/insertCardCredit: card has existed and bind memberId, fail insert
                    if($card['member_id']) return false;

                    //for Api/CardCredit/insertCardCredit: card has existed and bind memberId, no matter member_id match, bind the new member_id
                    //for Api/CardCredit/insertCardCredit: card_code has existed and not bind memberId, bind memberId now
                    $data['update_time'] = time();
                    if($this->where('card_id=%d', $card['card_id'])->data($data)->save()) {
                        return $card['card_id'];
                    } else {
                        return false;
                    }
                } else {
                    //for Kiosk/Api/verifyCreditCard: card has existed and bind memberId
                    //for Kiosk/Api/verifyCreditCard: card_code has existed and not bind memberId
                    return $card['card_id'];
                }
            } else {
                $data['create_time'] = time();
                if ($this->create($data)) {
                    return $this->add();
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    public function insertBody($data){
        if ($this->create($data)) {
            return $this->add();
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
        return $this->where($wh)->order('card_id')->select();
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
