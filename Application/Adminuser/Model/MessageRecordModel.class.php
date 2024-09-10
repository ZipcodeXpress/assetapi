<?php
namespace Adminuser\Model;
use Think\Model;

class MessageRecordModel extends Model{
    protected $trueTableName = 'message_record';

    public function updateLastMessageId($memberId, $lastMessageId){
        if(empty($memberId) && $lastMessageId) {
            return false;
        }
        $record = $this->getByMemberId($memberId);
        if($record) {
            $ret = $this->where(['member_id'=>$memberId])->data([
                'last_message_id' => $lastMessageId,
                'create_time'=>time()
            ])->save();
        } else {
            $ret = $this->data([
                'member_id' => $memberId,
                'last_message_id'=>$lastMessageId,
                'create_time' => time(),
            ])->add();
        }
        return $ret;
    }
}
