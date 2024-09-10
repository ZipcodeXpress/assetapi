<?php
namespace Zpi\Controller;
use Think\Controller;
use Common\Common;

class CliController extends Controller{

    static $_log = null;

    public function __construct(){
        if (!IS_CLI) {
            exit('请在cli模式下运行');
        }
        parent::__construct();

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('cli');
        }
    }

    /**
     * o_member_organization.charge_day 1 ~ 31
     * o_member_organization.last_charge_time

        //如果上一次charge的时间在今天之前
        if last_charge_time < (time() - time()%86400 - 1)
            //一个月的最后一天
            if date('j') == date('t')
                charge now
            //非一个月的最后一天，但是 charge_day
            else if date('j') == charge_day
                charge now

     */
    public function checkMemberOrganization(){

        $toChargeList = D('OMemberOrganization')->getToChargeList();

        foreach($toChargeList as $toCharge) {

            $memberPlan = D('OMemberOrganization')->getMember($toCharge['member_id']);
            if(empty($memberPlan)) {
                continue;
            }

            D('OCharge')->charge(
                $toCharge['member_id'],
                $memberPlan['organization_id'],
                $memberPlan['charge_rule'],
                'monthly_fee'
            );

            D('OMemberOrganization')->updateMemberApartment([
                'member_id'    => $toCharge['member_id'],
                'organization_id' => $toCharge['organization_id'],
                'unit_id'      => $toCharge['unit_id'],
                'apply_time'   => $toCharge['apply_time'],
            ], [
                'last_charge_time' => time(),
            ]);
        }
    }


    public function checkOverReserve  (){
        $now = time();
        //crontab only process once according to expire time
        $expire = strtotime(date("Y-m-d"));
        $toOverdueList = D('ProductRental')->getRentalList([
                    //pick timeout (2*24hour)
                    'reserve_time' => ['lt', $now - 2*86400],
                    'rental_time'  => ['exp', 'is null'],
                    'expire_time'  => ['neq', $expire],
                    ]);
        
        self::$_log->write([
                    'time' => date('Y-m-d H:i:s', time()),
                    'overdue charge' => count($toOverdueList),
                    'reserve_time' => ['lt', $now - 2*86400],
                    'rental_time'  => ['exp', 'is null'],
                    'expire_time'  => ['neq', $expire],
                ]);
        
        foreach($toOverdueList as $store) {    

            // update o_product_rental pick_expire time
            D('ProductRental')->updateStore($store['rental_id'], [
                     'expire_time' => $expire,
					 'rental_status_code' => '1',
                         ]);
			D('ProductInventory')->updateStore($store['product_inventory_id'], [
                     'update_time' => $now,
					 'product_status_code' => '1',
					 'member_id'=>'0',
                         ]);			 
            self::$_log->write([
                     'time' => date('Y-m-d H:i:s', time()),
                     'member_id' => $store['to_member_id'],
                     'rental_id' => $store['rental_id'],
                     'reserve_time' => $store['reserve_time'],
                     'expire_time' => $expire,
                      ]);

        }
         
   }
}
