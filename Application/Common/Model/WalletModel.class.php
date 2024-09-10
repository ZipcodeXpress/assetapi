<?php
/** new Wallet Model since 20170221
    subWallet will be much more complex according to the new charge rules

    public function subWallet($memberId, $amount, $frozenMoney, $ubi=0, $stArr = array(), $forceSubWallet=1){
        if $ubi
            calculate ubi amount
            sub ubi, insert statement
            sub money, insert statement

        ...
        ...
        if success return surplus amount or 0
        else return false
    }
*/
namespace Common\Model;
use Think\Model;
use Think\Log\Driver;

class WalletModel extends Model{

    private static $_logger = null;

    protected $_validate = array(
    );

    protected $_auto = array(
    );

    private function getLogger(){
        if (!isset(self::$_logger)) {
            self::$_logger = new \Think\Log\Driver\Logger('wallet');
        }
        return self::$_logger;
    }

    public function insertWallet($memberId){
        if ($this->create(array('member_id'=>$memberId))) {
            return $this->add();
        } else{
            return false;
        }
    }

    /* 用户加钱
     * @params    memberId      用户Id
     * @params    money         加金钱数，正数，用于加
     * @params    frozenMoney   扣押金数，正数，用于减
     * @array     stArr       对账单
     * @return    mixed
    */
    public function addWallet($memberId, $amount, $frozenMoney, $ubi = 0, $stArr = array()){
        $log = array(
            'result' => 'add_user_wallet_fail',
            'memberId' => $memberId,
            'money' => $amount,
            'frozenMoney' => $frozenMoney,
            'ubi' => $ubi,
        );
        if ($amount < 0 || $frozenMoney < 0 || $ubi < 0) {
            $log['status'] = 'params_error';
            $this->getLogger()->write(json_encode($log));
            return false;
        }

        $data = $this->where('member_id = %d', $memberId)->find();
        if (empty($data)) {
            $log['status'] = 'no_record';
            $log['error'] = $this->getDbError();
            $this->getLogger()->write(json_encode($log));
            return false;
        }

        $update = array('money'=>array('exp',"money + {$amount}"));
        if (!empty($frozenMoney)) {
            if ($data['frozen_money'] < $frozenMoney) {
                $log['status'] = 'frozenMoney_not_enough';
                $this->getLogger()->write(json_encode($log));
                return false;
            }
            $update['frozen_money'] = array('exp',"frozen_money - {$frozenMoney}");
        }

        if (!empty($ubi)) {
            $update['ubi'] = array('exp', "ubi + {$ubi}");
            $stArr['channel'] = 'ubi';
        } else {
            $stArr['channel'] = 'account';
        }

        $ret = $this->data($update)->where('member_id = %d', $memberId)->save();
        if (empty($ret)) {
            $log['status'] = 'update_error';
            $log['error'] = $this->getDbError();
            $this->getLogger()->write(json_encode($log));
            return false;
        } else{
            $log['result'] = 'add_user_wallet_succ';
            $log['before'] = $data;

            $data['money'] += $amount;
            $data['frozen_money'] -= $frozenMoney;
            $data['ubi'] += $ubi;
            $log['after'] = $data;

            $this->getLogger()->write(json_encode($log));
        }

        if($stArr) {
            $stArr['amount'] = max(abs($amount), abs($frozenMoney), abs($ubi));
            $Statement = D('Statement');
            $Statement->insertStatement($memberId, $stArr);
        }

        return $data;
    }

    /* 用户扣钱
     * @params    memberId      用户Id
     * @params    money         扣金钱数，正数，用于减
     * @params    frozenMoney   加押金数，正数，用于加
     * @array     stArr       对账单
     * @return    mixed
    */
    public function subWallet($memberId, $amount, $frozenMoney, $ubi=0, $stArr = array(), $forceSubWallet=1){
        $log = array(
            'result' => 'sub_user_wallet_fail',
            'memberId' => $memberId,
            'money' => $amount,
            'frozenMoney' => $frozenMoney,
            'ubi' => $ubi,
        );
        if ($amount < 0 || $frozenMoney < 0 || $ubi < 0) {
            $log['status'] = 'params_error';
            $this->getLogger()->write(json_encode($log));
            return false;
        }

        $Statement = D('Statement');

        if($frozenMoney) {
            $frozenMoney = $amount;
            $ubi = 0;//forzenMoney cannot use ubi
        }

        $data = $this->where('member_id = %d', $memberId)->find();
        $wallet = $data;
        if (empty($data)) {
            $log['status'] = 'no_record';
            $log['error'] = $this->getDbError();
            $this->getLogger()->write(json_encode($log));
            return false;
        }

        if($amount == 0) {
            /** only sub ubi */
            if($ubi > 0 && $data['ubi'] < $ubi) {
                $log['status'] = 'ubi not enough';
                $this->getLogger()->write(json_encode($log));
                return false;
            } else {
                $update['ubi'] = array('exp', "ubi - {$ubi}");
                $data['ubi'] -= $ubi;
                $stArr['amount'] = -$ubi;
                $ret = $this->data($update)->where('member_id = %d', $memberId)->save();
                if($ret) {
                    $stArr['channel'] = 'ubi';
                    $Statement->insertStatement($memberId, $stArr);
                    return 0;
                } else {
                    $log['status'] = 'sub ubi fail';
                    $this->getLogger()->write(json_encode($log));
                    return false;
                }
            }
        } else {
            /** sub money with ubi cost */
            $ubi = $ubi > 0;
        }

        /* sub ubi if has */
        $update = [];
        if($data['ubi'] > 0 && $ubi) {
            $umount = $amount * C('ubi_rate');
            if($umount < $data['ubi']) {
                $amount = 0;
                $update['ubi'] = array('exp', "ubi - {$umount}");
                $data['ubi'] -= $umount;
                $stArr['amount'] = -$umount;
            } else {
                $amount -= $data['ubi']/C('ubi_rate');
                if($forceSubWallet == 0 && $amount > 0 && $data['money'] < $amount) {
                    $log['status'] = 'money & ubi not enough, forceSubWallet 0';
                    $this->getLogger()->write(json_encode($log));
                    return false;
                }
                $update['ubi'] = 0;
                $stArr['amount'] = -$data['ubi'];
                $data['ubi'] = 0;
            }
            $ret = $this->data($update)->where('member_id = %d', $memberId)->save();
            if($ret) {
                $stArr['channel'] = 'ubi';
                $Statement->insertStatement($memberId, $stArr);
            }
        }

        if($amount == 0) {
            return 0;
        }

        /* sub money if has */
        $update = [];

        if ($data['money'] >= $amount) {
            $update = array('money'=>array('exp',"money - {$amount}"));
            $stArr['amount'] = -$amount;
            $data['money'] -= $amount;

            if ($frozenMoney) {
                $update['frozen_money'] = array('exp',"frozen_money + {$frozenMoney}");
                $data['frozen_money'] += $frozenMoney;
            }
            $amount = 0;
        } else {
            switch ($forceSubWallet) {
                case 0://money not enough, stop sub
                    $log['status'] = 'money not enough, forceSubWallet 0';
                    $this->getLogger()->write(json_encode($log));
                    return false;
                    break;
                case 1://sub money to 0
                    if($data['money'] > 0){
                        $update = array('money'=>0);
                        $stArr['amount'] = -$data['money'];
                        $amount -= $data['money'];
                        $data['money'] = 0;
                    } else {
                        return $amount;
                    }
                    break;
                case 2://sub money to whatever
                    $update = array('money'=>array('exp',"money - {$amount}"));
                    $stArr['amount'] = -$amount;
                    $data['money'] -= $amount;
                    $amount = 0;
                    break;
                default:
                    $log['status'] = 'wrong forceSubWallet';
                    $log['forceSubWallet'] = $forceSubWallet;
                    $this->getLogger()->write(json_encode($log));
                    return false;
            }
        }

        $stArr['channel'] = 'account';
        $ret = $this->data($update)->where('member_id = %d', $memberId)->save();
        if (empty($ret)) {
            $log['status'] = 'update_error';
            $log['error'] = $this->getDbError();
            $this->getLogger()->write(json_encode($log));
            return false;
        } else{
            $log['result'] = 'sub_user_wallet_succ';
            $log['before'] = $wallet;
            $log['after'] = $data;

            $this->getLogger()->write(json_encode($log));
        }

        $Statement->insertStatement($memberId, $stArr);
        return $amount;
    }

    public function getWallet($memberId){
        return $this->where('member_id = %d', $memberId)->find();
    }

    public function checkDiscount($memberId, $amount){
        //$amount has cover the tax
        $wallet = $this->where('member_id = %d', $memberId)->find();
        return $wallet['money'] >= $amount;
    }

    public function checkWallet($memberId){
        $wallet = $this->where('member_id = %d', $memberId)->find();
        return $wallet['money'] >= 0;
    }

    public function autoSupplement($memberId, $cardId){
        /**
            Api/CardCredit/insertCardCredit
            Kiosk/Api/bindAccount
         */
        $wallet = $this->where('member_id = %d', $memberId)->find();
        $money = $wallet['money'];
        if($money < 0) {
            $amount = abs($money);

            /* charge credit card */
            $stArr = [
                'statement_type' => 'autosupplement',
                'statement_desc' => 'autosupplement',
                'amount' => -$amount,
                'channel' => 'credit',
            ];
            $Charge = new \Common\Common\Charge();
            $pgResult = $Charge->charge($memberId, $amount, $stArr, C('charge_type.auto_supplement'), $cardId);
            if($pgResult['status']) {
                return $pgResult;
            } else {
                return $this->addWallet($memberId, $amount, 0, 0, $stArr);
            }
        }
        return false;
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
        return $this->where($wh)->order('member_id')->select();
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
