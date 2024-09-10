<?php
namespace Zpi\Controller;
use Common\Common;
use Think\Controller;

class StoreController extends BaseController {

    /**
     * @api {post} /Store/addMemberStore addMemberStore
     * @apiName addMemberStore
     * @apiGroup 22-MemberStore
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} toMember
     * @apiParam {String} zipcode
     * @apiParam {String} [note]
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'add member store info success',              
            '1' => 'need login',
            '2' => 'params isn't empty',
            '3' => 'add fail',              
     *
     * @apiSampleRequest
     */
    public function addMemberStore(){

        if(empty($this->_memberId)) { $this->ret(1);}

        //only support update field one by one

        $toMember     = I('request.toMember');
        $zipcode      = I('request.zipcode');
        $note         = I('request.note');

        //allow empty field, but forbid all empty
        if (empty(array_filter(I('request.')))) $this->ret(2);
        $storecode = mt_rand(100000, 999999);
        $fromMember = D('MemberProfile')->getProfile($this->_memberId);

        $insertM = D('MemberStore')->insertMemberStore(array_filter([
            'to_member' => $toMember,
            'from_member' => $fromMember['username'],
            'access_code' => $storecode,
            'zipcode' => $zipcode,
            'store_status' => 1,
            'note' => $note,
        ]));

        if($insertM) {
          $this->ret(0);
        }
        else 
        {
        	$this->ret(3);
        	}
    }
    
    /**
     * @api {post} /Store/getMemberStoreList getMemberStoreList
     * @apiName getMemberStoreList
     * @apiGroup 22-MemberStore
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} storestatus
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'add member store info success',              
            '1' => 'need login',
            '2' => 'wrong param'
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.storeList
     * @apiSuccess {Object}     data.storeList.id
     * @apiSuccess {String}     data.storeList.toMember
     * @apiSuccess {String}     data.storeList.fromMember
     * @apiSuccess {String}     data.storeList.accessCode
     * @apiSuccess {String}     data.storeList.zipcode
     * @apiSuccess {String}     data.storeList.storeStatus
     * @apiSuccess {String}     data.storeList.createTime
     * @apiSuccess {String}     data.storeList.note
     *
     * @apiSuccessExample {json} Success-Response:
       {
           "ret": 0,
           "msg": "Success",
           "data": {
               "storeList": [
                   {
                       "id": "10001",
                       "toMember": "tom",
                       "fromMember": "jim",
                       "accessCode": "123345"
                       "zipcode": "100000"
                       "storeStatus": "1"
                       "createTime": "1550734289",
                       "note":''
                   },
                   {
                       "id": "10002",
                       "toMember": "tom",
                       "fromMember": "jim",
                       "accessCode": "123345"
                       "zipcode": "100000"
                       "storeStatus": "1"
                       "createTime": "1550734299",
                       "note":''
                   },
               ],
           }
       }    
     *
     * @apiSampleRequest
     */
    public function getMemberStoreList(){

        if(empty($this->_memberId)) { $this->ret(1);}

        $storestatus     = I('request.storestatus');

        $fromMember = D('MemberProfile')->getProfile($this->_memberId);
       
        $store = D('MemberStore')->getMemberStoreList(array_filter([
            'from_member' => $fromMember['username'],
            'store_status' => $storestatus,
        ]));
        
        foreach ($store as $value) {
        	$storeList[] = array(
                    'id' => $value['id'],
                    'fromMember' => $value['from_member'],
                    'toMember' => $value['to_member'],
                    'accessCode' => $value['access_code'],
                    'zipcode' => $value['zipcode'],
                    'storeStatus' => $value['store_status'],
                    'createTime' => $value['create_time'],
                    'note' => $value['note']
                );
        	
        }

        $data['storeList'] = $storeList;
        $this->ret(0, $data);
    }
    
}
