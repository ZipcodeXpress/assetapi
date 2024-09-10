<?php
namespace Adminuser\Model;
use Think\Model;

class MessageSMSModel extends Model{
    protected $trueTableName = 'message_sms';

    public function getMessage($messageId){
        return $this->getByMessageId($messageId);
    }

    public function getMessageList($wh=['status'=>0], $limit=10){
        return $this->where($wh)->order('message_id asc')->limit($limit)->select();
    }

    public function insertMessage($phone, $message, $memberId,$countrycode) {
        $now = time();
       //$countrycode='0086';//C('COUTRYCODE');
        $data = [
            'phone' => $phone,
            'message' => $message,
            'member_id' => $memberId,
            'create_time' => $now,
            'country_code'=>$countrycode,
        ];
        return $this->data($data)->add();
    }

    public function updateMessage($messageId, $data) {
        return $this->where(['message_id' => $messageId])->data($data)->save();
    }
}
