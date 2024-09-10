<?php
namespace Common\Model;
use Think\Model;

class MessageModel extends Model{
    protected $trueTableName = 'message';

    public function getMessage($memberId, $messageId){
        if(empty($messageId)) {
            return false;
        }
        $wh = [
            'member_id' => $memberId,
            'message_id' => $messageId,
        ];
        return $this->where($wh)->find();
    }

    public function getMessageList($memberId, $lastMessageId){
        if(empty($memberId)) {
            return false;
        }
        $now = time();
        $wh = [
            'member_id'   => ['in'  , [$memberId, 0]],
            'send_time'   => ['elt' , $now],
            'expire_time' => [['gt'  , $now], ['exp', 'is null'], 'or'],
        ];
        if($lastMessageId) {
            //first message
            $wh['message_id'] = ['gt' , $lastMessageId];
        }
        return $this->field('message_id, title, create_time')->where($wh)->order('create_time desc')->select();
    }

    public function insertMessage($memberId, $title, $body, $sendTime=NULL, $expireTime=NULL){
        $now = time();
        $data = [
            'member_id' => $memberId,
            'title' => $title,
            'body' => $body,
            'send_time' => $sendTime ? : $now,
            'expire_time' => $expireTime,
            'create_time' => $now,
        ];
        return $this->data($data)->add();
    }
}
