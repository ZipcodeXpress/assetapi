<?php
namespace Common\Common;

class Charge {

    static $_log = null;
    public function __construct() {

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('charge');
        }
    }

    private function ret($status, $data, $msg) {
        $msgArr = [
            '0' => 'success',
        ];
        self::$_log->write(['status' => $status, 'data' => $data, 'msg' => $msg ? : ucfirst($msgArr[$status])]);
        return ['status' => $status, 'data' => $data, 'msg' => $msg ? : ucfirst($msgArr[$status])];
    }

    public function validCard($card, $isOldCard=true) {

        $ret = true;
        if(empty($card['card_code'])) { $ret = false;}
        if($isOldCard) {
            if(empty($card['payment_profile_id'])) { $ret = false;}
        }
        if(empty($card['exp_date'])) { $ret = false;}
        if(empty($card['card_holder_name'])) { $ret = false;}

        $y = substr($card['exp_date'], 2);
        $m = substr($card['exp_date'], 0, 2);
        if(date('y') > $y) {
            $ret = false;
        } else if(date('y') == $y) {
             if(intval(date('m')) > intval($m)) {
                $ret = false;
             }
        }

        if(empty($ret) && $isOldCard) {
            D('CardCredit')->updateCardById($card['card_id'], [
                'status' => C('authorize.error_expired_status'),
                'status_msg' => C('authorize.error_expired_msg'),
                'update_time' => time(),
            ]);
        }
        return $ret;
    }

    public function charge($memberId, $amount, $st, $chargeType=null, $cardId=null) {

        /** charge credit card */
        $Card = D('CardCredit');
        if(empty($cardId)) {
            $card = $Card->getDefaultCard($memberId, $scope='profile');
        } else {
            $card = $Card->getCardByMemberIdAndCardId($memberId, $cardId, $scope='profile');
        }
        if(empty($card) || empty($card['profile_id']) || empty($card['payment_profile_id'])) {
            return [
                'status' => C('authorize.error_empty_card_status'),
                'data' => null,
                'msg' => C('authorize.error_empty_card_msg'),
            ];
        }

        $pg = new \Common\Common\PayAuthorize();
        $pgResult = $pg->charge_payment_profile(
            $card['profile_id'],
            $card['payment_profile_id'],
            $amount
        );
        /**
        array (size=3)
          'status' => int 1
          'err' => string 'E00040' (length=6)
          'msg' => string 'Customer Profile ID or Customer Payment Profile ID not found.' (length=61)
        array (size=3)
          'status' => int 1
          'err' => string 'E00027' (length=6)
          'msg' => string 'The transaction was unsuccessful.' (length=33)
        array (size=7)
          'status' => int 0
          'msg' => string 'This transaction has been approved.' (length=35)
          'data' => array(
              'authCode' => string 'MZS5VR' (length=6)
              'transId' => string '40005587922' (length=11)
              'transHash' => string '2AD21BDA1C37EAE19D0E4B68008D0AF9' (length=32)
              'accountNumber' => string 'XXXX1358' (length=8)
              'accountType' => string 'Discover' (length=8)
            )
        */

        $orderId = D('OrderPayment')->insertOrder([
            'order_no' => $pgResult['data']['refId'],
            'type' => $chargeType,
            'channel' => 'credit',
            'amount' => $amount,
            'member_id' => $memberId,
            'status' => $pgResult['status'],
            'msg' => $pgResult['msg'],
            'create_time' => time(),
            'card_id' => $card['card_id'],
        ]);

        if($pgResult['status'] == 0) {
            /** insert statement */
            $stArr = array_merge(array(
                'amount' => -$amount,
                'channel' => 'credit',
                'order_payment_id' => $orderId,
            ), $st);
            $Statement = D('Statement');
            $Statement->insertStatement($memberId, $stArr);
        } else {
            $Notice = new \Common\Common\Notice();
            $Notice->notice(C('NOTICE.NT_CREDITCARD_CHARGE_FAIL'), $memberId, ['card_last4' => $card['card_last4']]);
        }

        if($card['card_id']) {
            $Card = D('CardCredit');
            $Card->updateCardById($card['card_id'], [
                'status' => $pgResult['status'],
                'status_msg' => $pgResult['msg'],
                'update_time' => time(),
            ]);
        }
        return $pgResult;
    }

    public function createProfile($memberId, $card, $withCvv2=true) {

        //TODO: check customer_profile, if exists, update_profile

        $member = D('Member')->getMemberById($memberId, 'member_id, email');
        if($memberId && $member) {
            $profile = D('MemberProfile')->getProfileById($memberId, '
                first_name,
                last_name,
                addressline1,
                addressline2,
                city,
                state
            ');
            $profile['address'] = $profile['addressline1'].$profile['addressline2'];
            unset($profile['addressline1']);
            unset($profile['addressline2']);
            $profile['zipcode'] = $card['zipcode'] ? : $profile['zipcode'];
        } else {
            return [
                'status' => 1,
                'err' => 'memberError',
                'msg' => 'empty member',
            ];
        }

        $payment = [
            'card_number' => $card['card_code'],//4111111111111111
            'exp_date'    => $card['exp_date'],//1020
            'cvv2'        => $card['cvv2'],//123
        ];

        if($withCvv2 === false) {
            unset($payment['cvv2']);
        }

        if((sizeof(array_filter($payment)) !== 3 && $withCvv2) || (sizeof(array_filter($payment)) !== 2 && !$withCvv2)) {
            return [
                'status' => 1,
                'err' => 'cardError',
                'msg' => 'wrong card info',
            ];
        }

        $pg = new \Common\Common\PayAuthorize();
        $pgResult =  $pg->create_profile($member, $profile, $payment);

        /**
        dump($pgResult);exit;
        array (size=3)
          'status' => int 0
          'profileId' => string '1501552724' (length=10)
          'paymentProfileId' => string '1501083235' (length=10)
        array (size=3)
          'status' => int 1
          'err' => string 'E00039' (length=6)
          'msg' => string 'A duplicate record with ID 1501552648 already exists.' (length=53)
        */
        return $pgResult;
    }

    public function createPaymentProfile($memberId, $card, $profileId, $withCvv2=true) {

        $payment = [
            'card_number' => $card['card_code'],//4111111111111111
            'exp_date'    => $card['exp_date'],//1020
            'cvv2'        => $card['cvv2'],//123
        ];

        if($withCvv2 === false) {
            unset($payment['cvv2']);
        }

        if((sizeof(array_filter($payment)) !== 3 && $withCvv2) || (sizeof(array_filter($payment)) !== 2 && !$withCvv2)) {
            return [
                'status' => 1,
                'err' => 'cardError',
                'msg' => 'wrong card info',
            ];
        }

        $pg = new \Common\Common\PayAuthorize();
        $pgResult =  $pg->create_payment_profile($profileId, $profile, $payment);

        /**
        dump($pgResult);exit;
        array (size=3)
          'status' => int 0
          'paymentProfileId' => string '1501083235' (length=10)
        array (size=3)
          'status' => int 1
          'err' => string 'E00039' (length=6)
          'msg' => string 'A duplicate customer payment profile already exists.' (length=52)
        */
        return $pgResult;
    }

    /**
     *  Zippora Charge Delay Fee (One Store One Charge)
     */
    public function chargeStoreFee($store) {
        self::$_log->write(['store' => $store, 'action' => 'charge.chargeStoreFee']);

        /** calculate expire fee */
        $modelPrice = D('CabinetBoxModel')->getModelPrice($store['boxId']);
        $totalChargedFee = 0;
        $now = time();
        //bugfix
        //if($store['pick_expire'] < $now)
        if($store['pickExpire'] < $now) {
            $store['pickFee'] = $modelPrice;
            $totalChargedFee   = $modelPrice;
        } else {
            $store['pickFee'] = 0;
            return $store;
        }

        /** sub wallet */
        $stArr = array(
            'statement_type' => 'zippora',
            'statement_desc' => 'penalty',
            'order_id' => $store['storeId'],
        );

        /* charge wallet */
        $amount = $totalChargedFee;
        $amount = D('Wallet')->subWallet($store['toMemberId'], $amount, 0, 1, $stArr);
        if($amount === 0) {
            return $store;
        } else if($amount === false) {
            $amount = $totalChargedFee;
        }

        /** charge credit card */
        $pgResult = $this->charge($store['toMemberId'], $amount, $stArr, C('charge_type.zippora'));

        if($pgResult['status']) {
            /** force sub wallet again */
            D('Wallet')->subWallet($store['toMemberId'], $amount, 0, 1, $stArr, 2);
        }
        return $store;
    }

    /**
     *  Zippora Charge Monthly Fee
     */
    public function chargeMonthlyFee($memberId, $price) {
        self::$_log->write(['amount' => $amount, 'action' => 'charge.chargeMonthlyFee']);

        /** sub wallet */
        $stArr = array(
            'statement_type' => 'zippora',
            'statement_desc' => 'monthly_fee',
        );

        $amount = $price;

        /* charge wallet */
        $amount = D('Wallet')->subWallet($memberId, $amount, 0, 1, $stArr);
        if($amount === 0) {
            return true;
        } else if($amount === false) {
            $amount = $price;
        }

        /** charge credit card */
        $pgResult = $this->charge($memberId, $amount, $stArr, C('charge_type.zippora'));

        if($pgResult['status']) {
            /** force sub wallet again */
            D('Wallet')->subWallet($memberId, $amount, 0, 1, $stArr, 2);
        }
        return true;
    }

    /**
     *  Ziplocker Charge Fee/Penalty or Refund
     *
     *  charge($deliver, $action, $chargePeriod, $orderFee/$boxFee/$bonus)
            $deliver
            $action   -------------- z_deliver_status
     */
    public function chargeDeliver($deliverId, $action) {
        self::$_log->write([
            'deliverId' => $deliverId,
            'action' => 'charge.ziplocker.'.$action,
        ]);

        $deliver = D('ZDeliver')->getByDeliverId($deliverId);
        if(empty($deliver)) {
            return false;
        }

        if($deliver['courier_id']) {
            $order = D('ZCourierOrder')->getByDeliverIdAndCourierId(
                $deliver['deliver_id'],
                $deliver['courier_id']
            );
        }

        /** with chargePeriod, charge or not, charge or refund, calculate $amount */

        /** sub wallet */
        $ZPriceConfig = C('z_price_config');
        if(empty($ZPriceConfig[$action]['type'])) {
            return true;
        }

        $chargeType = $ZPriceConfig[$action]['type'];
        $stArr = array(
            'statement_type' => 'ziplocker',
            'statement_desc' => $action.'_'.$chargeType,
            'order_id' => $deliverId,
        );

        /* charge wallet */
        $now = time();
        switch($action) {
            case 'order_success':
                $memberId = $deliver['from_member_id'];
                $chargeBase = $deliver['fee_total'];
                $chargeRate = 1;
                break;
            case 'order_cancel':
                if($deliver['status'] != C('z_deliver_status_code.order_success')) {
                    //only order_success refund
                    return true;
                }
                $memberId = $deliver['from_member_id'];
                $chargeBase = $deliver['fee_total'];
                if(empty($deliver['order_time'])) {
                    return false;
                }
                $chargeHours = ($now - $deliver['order_time']) / 3600;
                if($chargeHours < 2) {
                    $chargeRate = .8;
                } else if($chargeHours < 24) {
                    $chargeRate = .5;
                } else if($chargeHours < 48) {
                    $chargeRate = .2;
                } else {
                    $chargeRate = 0;
                }
                break;
            case 'store_success':
                $memberId = $deliver['from_member_id'];
                $chargeBase = $deliver['fee_box'];
                if(empty($deliver['order_time'])) {
                    return false;
                }
                $chargeHours = ($now - $deliver['order_time'] - 86400) / 3600;
                $chargeRate = $chargeHours > 0 ? ceil($chargeHours/24) : 0;//charge each 24hours
                break;
            case 'token_cancel':
                $memberId = $deliver['courier_id'];
                $chargeBase = $order['fee_total'];
                if(empty($deliver['token_time'])) {
                    return false;
                }
                $chargeHours = ($now - $deliver['token_time']) / 3600;
                if($chargeHours < .5) {
                    $chargeBase = 1;
                    $chargeRate = 1;
                } else if($chargeHours < 2) {
                    $chargeRate = .2;
                } else if($chargeHours < 4) {
                    $chargeRate = .5;
                } else if($chargeHours < 8) {
                    $chargeRate = .8;
                } else {
                    $chargeRate = 1;
                }
                break;
            case 'fetch_timeout':
                $memberId = $deliver['courier_id'];
                $chargeBase = $order['fee_total'];
                $chargeRate = 1;
                break;
            case 'deliver_success':
                $memberId = $deliver['courier_id'];
                $chargeBase = 1;
                if(empty($deliver['token_time'])) {
                    return false;
                }
                $chargeHours = ($now - $deliver['token_time'] - 86400) / 3600;//get hours
                $chargeRate = $chargeHours > 0 ? ceil($chargeHours) : 0;//charge each hour
                break;
            case 'pick_success':
                $memberId = $deliver['to_member_id'];
                if(empty($memberId)) {
                    return true;//no charge if receiver is not a member
                }
                $chargeBase = $deliver['fee_box'];
                if(empty($deliver['deliver_time'])) {
                    //bugfix: deliver_time must not be null
                    return false;
                }
                $chargeHours = ($now - $deliver['deliver_time'] - 86400) / 3600;//get hours
                $chargeRate = $chargeHours > 0 ? ceil($chargeHours/24) : 0;//charge each 24hours
                break;
            default:
                return true;
        }

        $totalChargedFee = $chargeBase * $chargeRate;
        $amount = $totalChargedFee;

        if($amount == 0) {
            return true;
        }

        if($chargeType === 'refund') {
            D('Wallet')->addWallet($memberId, $amount, 0, 0, $stArr);
        } else {
            $amount = D('Wallet')->subWallet($memberId, $amount, 0, 1, $stArr);
            if($amount === 0) {
                return true;
            } else if($amount === false) {
                $amount = $totalChargedFee;
            }

            /** charge credit card */
            $pgResult = $this->charge($memberId, $amount, $stArr, C('charge_type.ziplocker'));

            if($pgResult['status']) {

                if(in_array($action, [
                    'order_success',
                ])) {
                    return false;
                }

                /** force sub wallet again */
                D('Wallet')->subWallet($memberId, $amount, 0, 1, $stArr, 2);
            }
        }

        return true;
    }
}
