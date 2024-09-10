<?php
namespace Opr\Controller;
use Think\Controller;
use Common\Common;

class MessageSmsController extends BaseController {

    /**
     * @api {get} /MessageSms/getMessageList getMessageList
     * @apiName getMessageList
     * @apiGroup 19-MessageSMS

     * @apiParam {String} [limit]  每次获取记录条数，默认10条
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Number} msg
            '0' => 'success',               
            '2' => 'exceed max limit 100',              
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.limit 限制最大条数
     * @apiSuccess {String}   data.count 本次获取的条数
     * @apiSuccess {Object[]} data.smsList
     * @apiSuccess {Object}     data.smsList.sms
     * @apiSuccess {String}     data.smsList.sms.messageId
     * @apiSuccess {String}     data.smsList.sms.phone
     * @apiSuccess {String}     data.smsList.sms.message
     * @apiSuccess {String}     data.smsList.sms.createTime
     * @apiSuccess {String}     data.smsList.sms.countrycode
     *
     * @apiSuccessExample {json} Success-Response:
       {
           "ret": 0,
           "msg": "Success",
           "data": {
               "smsList": [
                   {
                       "messageId": "10001",
                       "phone": "88869966",
                       "message": "[ZipcodeXpress] Your package has arrived at Zippora Smart Locker 10003. Pickup code: TestSMS. Please pick up ASAP",
                       "createTime": "2018-03-02 02:56:50",
                       "countrycode":'0086'
                   },
                   {
                       "messageId": "10002",
                       "phone": "88869966",
                       "message": "[ZipcodeXpress] Your package has arrived at Zippora Smart Locker 10003. Pickup code: TestSMS. Please pick up ASAP",
                       "createTime": "2018-03-02 02:58:12",
                       "countrycode":"0086"
                   }
               ],
               "limit": "10",
               "count": 2
           }
       }
     *
     * @apiSampleRequest
     */
    public function getMessageList(){

        $limit = I('request.limit') ? : 10;
        $smsArr = D('MessageSMS')->getMessageList([
            'status' => 0
        ], $limit);

        if($limit > 100) {
            $this->ret(2);
        }

        $now = time();
        foreach ($smsArr as $value) {

            if(D('MessageSMS')->updateMessage($value['message_id'], [
                'status' => 1,
                'send_time' => $now,
            ])) {
                $smsList[] = array(
                    'messageId' => $value['message_id'],
                    'phone' => $value['phone'],
                    'message' => $value['message'],
                    'createTime' => $value['create_time'] ? date('Y-m-d H:i:s', $value['create_time']) : '',
                    'countrycode'=>$value['country_code']
                );
            }
        }

        $data['smsList'] = $smsList ? : [];
        $data['limit'] = $limit;
        $data['count'] = sizeof($smsList);
        $this->ret(0, $data);
    }

    /**
     * @api {get} /MessageSms/updateMessage updateMessage
     * @apiName updateMessage
     * @apiGroup 19-MessageSMS

     * @apiParam {String} messageId
     * @apiParam {String} status    2:发送成功， 3:发送失败
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Number} msg
            '0' => 'update success',               
            '2' => 'wrong param',                   
            '3' => 'fail to update status',             
            '4' => 'message not found',                 
            '5' => 'wrong message status',                  
     *
     * @apiSuccessExample {json} Success-Response:
       {
           "ret": 0,
           "msg": "Update Success",
       }
     *
     * @apiSampleRequest
     */
    public function updateMessage(){

        $messageId = I('request.messageId');
        $status = I('request.status');

        if(empty($messageId)) {
            $this->ret(2);
        }

        if(!in_array($status, [2, 3])) {
            $this->ret(2);
        }

        $message = D('MessageSMS')->getMessage($messageId);
        if(empty($message)){
            $this->ret(4);
        }
        if($message['status'] != 1) {
            $this->ret(5);
        }

        $data = [
            'status' => $status,
        ];
        if($status == 2) {
            $data['sent_time'] = time();
        }

        if(D('MessageSMS')->updateMessage($messageId, $data)) {
            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }
}
