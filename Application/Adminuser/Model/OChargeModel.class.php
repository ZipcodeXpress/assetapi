<?php
namespace Adminuser\Model;
use Think\Model;

/***
CREATE TABLE `o_charge` (
  `charge_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '收费记录ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `apartment_id` int(11) NOT NULL COMMENT '公寓ID',
  `charge_rule` text NOT NULL COMMENT '收费规则',
  `charge_type` varchar(20) NOT NULL COMMENT '类型：signup_fee, monthly_fee, ...',
  `charge_channel` varchar(255) NOT NULL COMMENT '支付渠道：online, offline',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `paid_time` int(10) DEFAULT NULL,
  `paid_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认0未知状态；1缴费成功；2缴费失败',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `extra` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`charge_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='zippora收费信息表';
*/

class OChargeModel extends Model{

    public function insertCharge($data){

        $data['create_time'] = time();
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    /** 
     *  Zippora Charge By o_apartment.charge_rule
     *
     *  Zpi/Login/verifyAccount  signup_fee
     *  Zpi/Zippora/bindApartment  signup_fee
     *  Zpi/Cli/checkMemberApartment  monthly_fee
     *  Cabinet/Zippora/commitForPick  box_penalty
     */
    public function charge($memberId, $apartmentId, $chargeRule, $chargeType='signup_fee', $extra=[]) {

        $chargeRuleArr = json_decode($chargeRule, true);

        $stArr = [
            'statement_type' => 'zippora',
            'statement_desc' => $chargeType,
        ];

        //parse box_penalty rule
        if($chargeType == 'box_penalty') {
            if(empty($extra['boxModelId'])) return false;
            if(empty($extra['storeTime'])) return false;
            $boxModelId = $extra['boxModelId'];
            $chargeRuleArr[$chargeType] = $chargeRuleArr[$chargeType][$boxModelId];
            if(empty($chargeRuleArr[$chargeType])) return false;
            if(empty($chargeRuleArr[$chargeType]['grace_day'])) return false;
            $graceTime = $extra['storeTime'] + $chargeRuleArr[$chargeType]['grace_day'] * 86400;
            $chargeRuleArr[$chargeType]['amount'] *= ceil((time() - $graceTime)/86400);
            if($chargeRuleArr[$chargeType]['amount'] < 0) return false;

            $stArr = array_merge([
                'order_id' => $extra['storeId'],
                'extra' => json_encode([
                    'store_time' => $extra['storeTime'],
                    'overdue_days' => ceil((time() - $graceTime)/86400),
                ]),
            ], $stArr);
        }

        if(isset($chargeRuleArr[$chargeType]) && !empty($chargeRuleArr[$chargeType])) {

            if($chargeRuleArr[$chargeType]['amount'] > 0) {

                $chargeArr = [
                    'member_id' => $memberId,
                    'apartment_id' => $apartmentId,
                    'charge_rule' => $chargeRule,
                    'charge_type' => $chargeType,
                    'charge_channel' => $extra['payOffline'] ? 'offline' : ($chargeRuleArr[$chargeType]['pay_online'] ? 'online' : 'offline'),
                    'amount' => $chargeRuleArr[$chargeType]['amount'] ? : 0,
                ];

                $amount = $chargeRuleArr[$chargeType]['amount'];

                if($chargeRuleArr[$chargeType]['pay_online'] && $amount > 0) {
                    //sub wallet
                    $amount = D('Wallet')->subWallet($memberId, $amount, 0, 1, $stArr);

                    if($amount === 0) {
                        //pass
                    } else if($amount === false) {
                        $amount = $chargeRuleArr[$chargeType]['amount'];
                    }

                    //charge credit card
                    $Charge = new \Common\Common\Charge();
                    $pgResult = $Charge->charge($memberId, $amount, $stArr, C('charge_type.zippora'));

                    if($pgResult['status']) {
                        //force sub wallet again
                        D('Wallet')->subWallet($memberId, $amount, 0, 1, $stArr, 2);
                    }

                    $chargeArr['paid_time'] = time();
                    $chargeArr['paid_status'] = 1;
                }

                //insert o_charge
                $this->insertCharge($chargeArr);
            }   
        }   
        //no value return, just process
        return $chargeArr;
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
        return $this->where($wh)->order('charge_id')->select();
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
    
    
    /** 
     *  Zippora Charge By crontab everyday overdue order
     *
     *  Zpi/Cli/checkOverdue  box_penalty
     */
    public function overduecharge($memberId, $apartmentId, $chargeRule, $chargeType='box_penalty', $extra=[]) {

        $chargeRuleArr = json_decode($chargeRule, true);

        $stArr = [
            'statement_type' => 'zippora',
            'statement_desc' => $chargeType,
        ];

        //parse box_penalty rule
        if($chargeType == 'box_penalty') {
            if(empty($extra['boxModelId'])) return false;
            if(empty($extra['storeTime'])) return false;
            $boxModelId = $extra['boxModelId'];
            $chargeRuleArr[$chargeType] = $chargeRuleArr[$chargeType][$boxModelId];
            if(empty($chargeRuleArr[$chargeType])) return false;
            if(empty($chargeRuleArr[$chargeType]['grace_day'])) return false;
            $graceTime = $extra['storeTime'] + $chargeRuleArr[$chargeType]['grace_day'] * 86400;
            if($chargeRuleArr[$chargeType]['amount'] < 0) return false;

            $stArr = array_merge([
                'order_id' => $extra['storeId'],
                'extra' => json_encode([
                    'store_time' => $extra['storeTime'],
                    'overdue_days' => ceil((time() - $graceTime)/86400),
                ]),
            ], $stArr);
        }

        if(isset($chargeRuleArr[$chargeType]) && !empty($chargeRuleArr[$chargeType])) {

            if($chargeRuleArr[$chargeType]['amount'] > 0) {
                $chargeArr = [
                    'member_id' => $memberId,
                    'apartment_id' => $apartmentId,
                    'charge_rule' => json_encode($chargeRuleArr[$chargeType]),
                    'charge_type' => $chargeType,
                    'charge_channel' => $extra['payOffline'] ? 'offline' : ($chargeRuleArr[$chargeType]['pay_online'] ? 'online' : 'offline'),
                    'amount' => $chargeRuleArr[$chargeType]['amount'] ? : 0,
                ];

                $amount = $chargeRuleArr[$chargeType]['amount'];

                if($chargeRuleArr[$chargeType]['pay_online'] && $amount > 0) {
                    //sub wallet 2 can sub money to negative
                    D('Wallet')->subWallet($memberId, $amount, 0, 0, $stArr, 2);
                    
                    $chargeArr['paid_time'] = time();
                    $chargeArr['paid_status'] = 1;
                }

                //insert o_charge
                $this->insertCharge($chargeArr);
            }   
        }   
        //no value return, just process
        return $chargeArr;
    } 
}
