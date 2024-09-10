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
     * o_member_apartment.charge_day 1 ~ 31
     * o_member_apartment.last_charge_time

        //如果上一次charge的时间在今天之前
        if last_charge_time < (time() - time()%86400 - 1)
            //一个月的最后一天
            if date('j') == date('t')
                charge now
            //非一个月的最后一天，但是 charge_day
            else if date('j') == charge_day
                charge now

     */
    public function checkMemberApartment(){

        $toChargeList = D('OMemberApartment')->getToChargeList();

        foreach($toChargeList as $toCharge) {

            $memberPlan = D('OMemberApartment')->getMember($toCharge['member_id']);
            if(empty($memberPlan)) {
                continue;
            }

            D('OCharge')->charge(
                $toCharge['member_id'],
                $memberPlan['apartment_id'],
                $memberPlan['charge_rule'],
                'monthly_fee'
            );

            D('OMemberApartment')->updateMemberApartment([
                'member_id'    => $toCharge['member_id'],
                'apartment_id' => $toCharge['apartment_id'],
                'unit_id'      => $toCharge['unit_id'],
                'apply_time'   => $toCharge['apply_time'],
            ], [
                'last_charge_time' => time(),
            ]);
        }
    }

    public function checkNotice(){
        ini_set('default_socket_timeout', -1);

        $key = 'async_notice';
        $client = S(array_merge(C('redis_config'), array('persistent'=>true)));
        $Notice = new \Common\Common\Notice();
        while($data = $client->proxy('BLPOP', $key, 0)){
            $params = json_decode($data[1], true);
            sleep(1);
            $ret = $Notice->notice($params['notice_tpl'], $params['member_id'], $params['data']);
            $log = array_merge($params, ['sendRet' => $ret]);
            self::$_log->write($log);
        }
    }

    public function checkDeliver(){

        $now = time();
        $where = [
            [
                //pay timeout (10min)
                'create_time' => ['lt', $now - 600],
                'status' => C('z_deliver_status_code.pay_wait')
            ], [
                //store timeout (48hour)
                'order_time' => ['lt', $now - 86400*2],
                'status' => C('z_deliver_status_code.order_success')
            ], [
                //fetch timeout (8hour)
                'order_time' => ['lt', $now - 3600*8],
                'status' => C('z_deliver_status_code.token_success')
            ],
            '_logic' => 'or'
        ];

        $toUpdateList = D('ZDeliver')->getDeliverList($where);

        $log = [
            'time' => date('Y-m-d H:i:s', time()),
            'toUpdateListCount' => count($toUpdateList),
            'toUpdateList' => [],
        ];

        foreach($toUpdateList as $deliver) {
            switch($deliver['status']) {
                case C('z_deliver_status_code.pay_wait'):
                    $log['toUpdateList'][$deliver['deliver_id']] = 'pay_timeout';
                    D('ZDeliver')->updateDeliverStatus($deliver['deliver_id'], 'pay_timeout');
                    break;
                case C('z_deliver_status_code.order_success'):
                    $log['toUpdateList'][$deliver['deliver_id']] = 'store_timeout';
                    D('ZDeliver')->updateDeliverStatus($deliver['deliver_id'], 'store_timeout');
                    break;
                case C('z_deliver_status_code.token_success'):
                    $log['toUpdateList'][$deliver['deliver_id']] = 'fetch_timeout';
                    D('ZDeliver')->updateDeliverStatus($deliver['deliver_id'], 'fetch_timeout');

                    //charge courier penalty, cancel courier order
                    $Charge = new \Common\Common\Charge();
                    $Charge->chargeDeliver($deliver['deliver_id'], 'fetch_timeout');

                    D('ZCourierOrder')->updateOrderStatus($deliver['deliver_id'], 'fetch_timeout', [
                        'cancel_time' => time(),
                        'cancel_reason' => 'timeout to fetch',
                    ]);
                    break;
                default:
                    break;
            }
        }

        self::$_log->write($log);
    }

    public function checkStore(){

        $now = time();
        $toUpdateList = D('OStore')->getStoreList([
            //pick timeout (3*24hour)
            'store_time' => ['lt', $now - 3*86400],
            'pick_time'  => ['exp', 'is null'],
        ]);

        self::$_log->write([
            'time' => date('Y-m-d H:i:s', time()),
            'toUpdateListCount' => count($toUpdateList),
        ]);

        foreach($toUpdateList as $store) {
            // async send notice
            S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
                'notice_tpl' => C('NOTICE.NT_ZIPPORA_OVERDUE_NOTICE'),
                'member_id' => $store['to_member_id'],
                'data' => [
                    'cabinet_id' => $store['cabinet_id'],
                    'pick_code' => $store['pick_code'],
                ]
            ]));
        }

        //send pick mail within 3 day  (3*24hour)
        $toList = D('OStore')->getStoreList([             
                    'store_time' => ['egt', $now - 3*86400],
                    'pick_time'  => ['exp', 'is null'],
        ]);

        self::$_log->write([
                         'time' => date('Y-m-d H:i:s', time()),
                         'toList' => count($toList),
        ]);

        foreach($toList as $store) {
           S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
               'notice_tpl' => C('NOTICE.NT_ZIPPORA_HAS_PACKAGE_TO_PICK'),
               'member_id' => $store['to_member_id'],
               'data' => [
                     'cabinet_id' => $store['cabinet_id'],
                     'pick_code' => $store['pick_code'],
                ]
            ]));
        }
    }

    public function checkSms(){

        $now = time();
        $toUpdateList = D('MessageSMS')->getMessageList([
            'status' => 1,
            'send_time' => ['lt', $now - 300],//5 min
        ]);

        self::$_log->write([
            'time' => date('Y-m-d H:i:s', time()),
            'toUpdateListCount' => count($toUpdateList),
        ]);

        foreach($toUpdateList as $message) {
            if(D('MessageSMS')->updateMessage($message['message_id'], [
                'status' => 0,
            ])) {
                self::$_log->write([
                    'time' => date('Y-m-d H:i:s', time()),
                    'updatedMessageId' => $message['message_id'],
                ]);
            }
        }
    }

    public function checkOverdue(){
        $now = time();
        //crontab only process once according to expire time
        $expire = strtotime(date("Y-m-d"));
        $toOverdueList = D('OStore')->getStoreList([
                    //pick timeout (3*24hour)
                    'store_time' => ['lt', $now - 3*86400],
                    'pick_time'  => ['exp', 'is null'],
                    'pick_expire'  => ['neq', $expire],
                    ]);
        
        self::$_log->write([
                    'time' => date('Y-m-d H:i:s', time()),
                    'overdue charge' => count($toOverdueList),
                    'store_time' => ['lt', $now - 3*86400],
                    'pick_time'  => ['exp', 'is null'],
                    'pick_expire'  => ['neq', $expire],
                ]);
        
        foreach($toOverdueList as $store) {
                
            $toBoxModel = D('CabinetBox')->getMember([
                     'box_id'  => ['eq', $store['box_id']],
                     'cabinet_id'  => ['eq', $store['cabinet_id']],
                     ]);
            $apartmentid = D('OApartmentCabinet')->getApartmentCabinet($store['cabinet_id']);
            $apartment = D('OApartment')->getMember($apartmentid['apartment_id']);
            $ret = D('OCharge')->overduecharge(
                     $store['to_member_id'],
                     $apartment['apartment_id'],
                     $apartment['charge_rule'],
                     'box_penalty', [
                          'storeId' => $store['store_id'],
                          'boxModelId' => $toBoxModel['box_model_id'],
                          'storeTime' => $store['store_time'],
                          ]
                     );
            
            if($ret['paid_status']==1) {
            // update o_store pick_expire time
            D('OStore')->updateStore($store['store_id'], [
                     'pick_expire' => $expire,
                         ]);
            self::$_log->write([
                     'time' => date('Y-m-d H:i:s', time()),
                     'member_id' => $store['to_member_id'],
                     'apartment_id' => $apartment['apartment_id'],
                     'charge_rule' => $ret['charge_rule'],
                     'storeId' => $store['store_id'],
                     'box_model_id' => $toBoxModel['box_model_id'],
                     'store_time' => $store['store_time'],
                     'pick_expire' => $expire,
                     'box_id' => $store['box_id'],
                     'cabinet_id' => $store['cabinet_id'],
                      ]);
            }

        }
         
   }
}
