<?php
namespace Adminuser\Model;
use Think\Model;

class StatementModel extends Model{
    protected $trueTableName = 'statement';

    public function getStatementList($memberId, $page=NULL, $startDate=NULL, $endDate=NULL){
        $whereStr = 'member_id=%d';
        if($startDate && $endDate) {
            $whereStr .= sprintf(' AND create_time > %s AND create_time < %s', $startDate, $endDate);
        }
        if(isset($page)) {
            return $this->where($whereStr, $memberId)->order('create_time desc, statement_id desc')->page(intval($page).','.C('BILL_PAGE_SIZE'))->select();
        } else {
            return $this->where($whereStr, $memberId)->order('create_time desc, statement_id desc')->select();
        }
    }

    public function countStatementList($memberId, $startDate=NULL, $endDate=NULL){
        $whereStr = 'member_id=%d';
        if($startDate && $endDate) {
            $whereStr .= sprintf(' AND create_time > %s AND create_time < %s', $startDate, $endDate);
        }
        return $this->where($whereStr, $memberId)->count();
    }

    public function getStatement($statementId){
        return $this->where('statement_id=%d', $statementId)->find();
    }

    public function getStatementText($statement, $type = 'list'){
        $statementConf = C('statement_config');
        $text = $type == 'list' ? $statementConf[$statement['statement_type']]['child'][$statement['statement_desc']]['listText'] : $statementConf[$statement['statement_type']]['child'][$statement['statement_desc']]['detailText'];
        $extra = empty($statement['extra']) ? array() : json_decode($statement['extra'], true);

        foreach ($extra as $key => $value) {
            $search = sprintf('{%s}', $key);
            $text = str_replace($search, $value, $text);
        }
        return $type == 'list' ? ucwords($text) : ucfirst($text);
    }

    public function formatDateTime($timestamp, $now = null) {
        /*
            time1 time2
            今天  13:00
            昨天  09:00
            周二  11-02
        */
        if (empty($now)) {
            $now = time();
        }
        $dateArr = array();
        $date = date('d/m/Y', $timestamp);
        if($date == date('d/m/Y', $now)) {
            $dateArr['week'] = '今天';
            $dateArr['date'] = date('H:i', $timestamp);
        } else if($date == date('d/m/Y',$now - (24 * 60 * 60))) {
            $dateArr['week'] = '昨天';
            $dateArr['date'] = date('H:i', $timestamp);
        } else {
            $dayOfWeek = date("w", $timestamp);
            $dayArr = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
            $dateArr['week'] = $dayArr[$dayOfWeek];
            $dateArr['date'] = date('m-d', $timestamp);
        }
        return $dateArr;
    }

    public function insertStatement($memberId, $stArr) {

        $Wallet = D('Wallet');
        $wallet = $Wallet->getWallet($memberId);

        $moneyArr = array(
            'member_id'=>$wallet['member_id'],
            'money'=>$wallet['money'],
            'frozen_money'=>$wallet['frozen_money'],
            'ubi'=>$wallet['ubi'],
            'create_time'=>time()
        );
        $stArr += $moneyArr;

        if(is_array($stArr['extra'])) {
            $stArr['extra'] = json_encode($stArr['extra']);
        }

        return $this->data($stArr)->add();
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
        return $this->where($wh)->order('statement_id')->select();
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
