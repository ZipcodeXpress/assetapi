<?php
namespace Zpi\Controller;
use Think\Controller;

class StatementController extends BaseController {

    /**
     * @api {get} /statement/getStatementList getStatementList
     * @apiName getStatementList
     * @apiGroup 06-Statement
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   [type] 可选默认是 'all', 还可选 'recharge', 'zippora'
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',                   
            '1' => 'need login',                   
            '2' => 'need type',                   
            '3' => 'invalid type',                   
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.list
     * @apiSuccess {Object} data.list.statement
     * @apiSuccess {String} data.list.statement.statementId "5709",
     * @apiSuccess {String} data.list.statement.title "Upgrade Rank"
     * @apiSuccess {String} data.list.statement.desc "Store penalty store time 2018-02-02 02:10:41 overdue 2 days",
     * @apiSuccess {String} data.list.statement.amount -85.50
     * @apiSuccess {String} data.list.statement.channel "credit card" 支付方式
     * @apiSuccess {String} data.list.statement.money "2001.00" 当时钱包余额
     * @apiSuccess {String} data.list.statement.createTime "2017-01-21 01:38:57" 创建时间
     *
     * @apiSampleRequest
     */
    public function getStatementList() {

        $type = I('request.type', 'all');
        $stTypeConfig = C('statement_config');

        if (empty($type)) { $this->ret(2);} 
        if ($type!= 'all' && empty($stTypeConfig[$type])) { $this->ret(3);} 

        $Statement = D('Statement');
        $statementArr = $Statement->getStatementList($this->_memberId);

        $list = array();
        $now = time();
        foreach ($statementArr as $key => $value) {
            if($type != 'all') {
                if (!in_array($value['statement_desc'], array_keys($stTypeConfig[$type]['child']))) {
                    continue;
                }
            }

            $payChannel = C('pay_channel');

            $desc = '';
            if($value['extra']) {
                $extra = json_decode($value['extra'], true);
                if($extra['store_time']) {
                    $desc .= 'store time ';
                    $desc .= date('Y-m-d H:i:s', $extra['store_time']);
                }
                if($extra['overdue_days']) {
                    $desc .= ' overdue ';
                    $desc .= $extra['overdue_days'].' days';
                }
            }
            $list[] = array(
                'statementId' => $value['statement_id'],
                'type' => $stTypeConfig[$value['statement_type']]['text'],
                'title' => $Statement->getStatementText($value, 'list'),
                'desc' => (strlen($Statement->getStatementText($value, 'detail')) > 0 && strlen($desc) > 0) ? $Statement->getStatementText($value, 'detail'). ' '.$desc : $Statement->getStatementText($value, 'detail'),
                'amount' => floatval($value['amount']),
                'channel' => $payChannel[$value['channel']],
                'createTime' => date('Y-m-d H:i:s', $value['create_time']),
                'money' => $value['money'],
                'frozenMoney' => $value['frozen_money'],
            );
        }
        $list = [
            'list' => $list,
        ];
        $this->ret(0, $list);
    }
}
