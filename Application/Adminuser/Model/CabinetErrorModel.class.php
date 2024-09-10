<?php
namespace Adminuser\Model;
use Think\Model;

class CabinetErrorModel extends Model{
    protected $trueTableName = 'cabinet_error';

    public function getCabinetError($errorId){
        return $this->where("error_id=%d", $errorId)->find();
    }

    public function insertCabinetError($cabinetId, $error){
        $data = [
            'cabinet_id' => $cabinetId, 
            'create_time' => time(),
        ];

        if(isset($error['methodName'])) {
            $data['method_name'] = $error['methodName'];
        }

        if(isset($error['message'])) {
            $data['message'] = $error['message'];
        }

        if(isset($error['stackTrace'])) {
            $data['stack_trace'] = $error['stackTrace'];
        }

        if(isset($error['memo'])) {
            $data['memo'] = $error['memo'];
        }

        if ($this->create($data)) {

            if($data['message']) {
                $body = "Cabinet$cabinetId report an error at ".date('Y-m-d H:i:s', time()).": ".$data['message'];
                $Email = new \Common\Common\Email();
                $Email->sendByPHPMailer([
                    'mailfrom' => C('SMTP_USER_EMAIL'),
                    'mailtoArr' => [
                        'service@gounibox.com',
                        'lijun@unibox.com.cn',
                        'jim@unibox.com.cn',
                        'tangzhen@unibox.com.cn',
                        'lianggan@unibox.com.cn',
                    ],
                    'subject' => 'Cabinet Error Report',
                    'body' => $body,
                ]);
            }

            return $this->add();
        } else {
            return false;
        }
    }

    public function getCabinetErrorList($where = array()){
        return $this->where($where)->select();
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
        return $this->where($wh)->order('error_id')->select();
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
