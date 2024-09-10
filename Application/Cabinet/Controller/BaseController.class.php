<?php
namespace Cabinet\Controller;
use Think\Controller;
use Common\Common;

class BaseController extends Controller {

    protected $_cabinetId = null;
    protected $_zipcode = null;
    protected $_apartmentId = null;
    protected $res = [];

    protected static $_log = null;

    public function __construct($t) {
        
        parent::__construct();

        if (!isset($this->_log)) {
            $this->_log = new \Think\Log\Driver\Logger($t);
        }

        if (!in_array(ACTION_NAME, [
            'getaccesstoken',
            'updatesequence',
            'initdata'
        ])) {

            $token  = I('request.accessToken');
            $key    = $this->getAccessTokenKey($token);
            $data   = S(C('memcache_config'))->get($key);

            if (!($data && $data['cabinetId'])) { $this->ret(1);}

            $this->_cabinetId = $data['cabinetId'];
            $this->_zipcode = $data['zipcode'];
            if($t == 'zippora') {
                $apt = D('OOrganizationCabinet')->getApartmentCabinet($this->_cabinetId);
                $this->_apartmentId = $apt['organization_id'];
            }
        }

        $this->_log->write([
            'method' => REQUEST_METHOD,
            'input'  => I('post.'),
        ]);
    }

    /* 格式化输出
     * @params    type       数据类型
     * @params    arr        数据源，支持多个数据源，如：$this->formatOutput('member', $arr1, $arr2, $arr3);
     * @return    mixed
    */
    public function formatOutput($type) {
        $arr = array();
        $args = func_get_args();
        foreach ($args as $value) {
            if (is_array($value)) {
                $arr = array_merge($arr, $value);
            }
        }

        if(empty($arr)) {
            return false;
        }

        switch($type) {
            case 'member': //会员信息数据
                $out = array(
                    'memberId'        => isset($arr['member_id']) ? $arr['member_id'] : 0,
                    'memberName'        => isset($arr['memberName']) ? $arr['memberName'] : $arr['first_name'].' '.$arr['last_name'],
                    'email'           => isset($arr['email']) ? $arr['email'] : '',
                    'status'          => $arr['status'] == C('member.status_locked') ? 1 : 0,
                    'money'           => isset($arr['money']) ? $arr['money'] : 0,
                    'frozenMoney'     => isset($arr['frozen_money']) ? $arr['frozen_money'] : 0,
                );
                break;
            case 'courier': //会员信息数据
                $out = array(
                    'courierId'        => isset($arr['courier_id']) ? $arr['courier_id'] : 0,
                    'courierName'      => $arr['courier_name'],
                );
                break;
            case 'couriercompany': //会员信息数据
                $out = array(
                    'courierId'        =>isset($arr['courier_id']) ? $arr['courier_id'] : 0,
                    'courierName'      =>isset($arr['company_name']) ? $arr['company_name'] : 0,
                           );
                break;

            case 'picks':
                break;
            case 'stores':
                break;
            default:
                $out = false;
        }
        return $out;
    }

    protected function ret($ret = 0, $data = null, $msg = '') {

        if(is_array($ret)) {
            list($ret, $data, $msg) = array_values($ret);
        }

        $msg = $msg ? : ucfirst(L(strtolower(CONTROLLER_NAME).'.'.strtolower(ACTION_NAME).'.'.$ret));

        $this->_log->write([
            'method' => REQUEST_METHOD,
            'input'  => I('post.'),
            'output' => [
                'ret' => $ret,
                'msg' => $msg,
                'data' => strlen(json_encode($data)) < 2000 ? $data : 'data is too long to show'
            ]
        ]);

        if (IS_POST) {
            $this->ajaxReturn([
                'ret' => $ret,
                'msg' => $msg,
                'data' => $data ? : (object)null,
            ]);
        } else {
            if ($ret == 0) {
                $this->success($msg, '/', 3);
            } else {
                $this->error($msg, '/', 5);
            }
        }
    }

    public function getAccessTokenKey($token) {
        return sprintf('cabinet_token_%s', $token);
    }

    public function getAccessToken() {

        #exit('{"ret":0,"msg":"Get Access Token Success","data":{"accessToken":"cfcd1e5477e465230ace070d70a78e1f","expire":86400,"cabinetId":100101,"address":"California XXXX"}}');

        $apiKey     = I('post.apiKey');
        $kts        = I('post.kts');
        $cabinetId  = I('post.cabinetId', '');
        $sign       = I('post.sign');

        if (empty($apiKey)) { $this->ret(1);}
        if (empty($kts)) { $this->ret(2);}
        if (empty($cabinetId)) { $this->ret(3);}
        if (empty($sign)) { $this->ret(4);}

        $cabinet = D('Cabinet')->getCabinet($cabinetId);

        if (empty($cabinet)) { $this->ret(5);}

        $apiSecret = $cabinet['api_secret'];
        if (empty($apiSecret)) { $this->ret(6);}

        //校验签名
        $genSign = md5("apiKey=".$apiKey."&apiSecret=".$apiSecret."&timestamp=".$kts."&cabinetId=".$cabinetId);
        if ($genSign != $sign) { $this->ret(7, [
                'debugSign' => $genSign
            ]);
        }

        //生成token
        $token = md5($apiKey.$sign.rand(1000,9999));
        $expire = 60*60*24;
        $data = array(
            'cabinetId'=>$cabinetId,
            'zipcode'=>$cabinet['zipcode'],
        );
        $key = $this->getAccessTokenKey($token); //获取token缓存key
        S(C('memcache_config'))->set($key, $data, $expire);

        $this->ret(0, [
            'accessToken' => $token,
            'expire' => $expire,
            'cabinetId' => $cabinet['cabinet_id'],
            'address' => $cabinet['address'],
            'zipcode' => $cabinet['zipcode'],
            'serviceType' => $cabinet['service_type'],
        ]);
    }

    public function checkAccessToken() {
        $this->ret(0);
    }

    //http://www.en.unibox.com.cn/cabinet/api/initBoxConfig?accessToken=7a5596563c59dda42a8f4333ae7c5b50
    public function initBoxConfig() {

        //init cabinet_box with cabinet_body, cabinet_body_box
        $boxArr = [];
        $bodyArr = D('CabinetBody')->getBodyList(['cabinet_id'=>$this->_cabinetId]);
        $now = time();
        foreach($bodyArr as $body) {
            $bodyBoxArr = D('CabinetBodyBox')->getBodyBoxList(['body_model_id' => $body['body_model_id']]);
            foreach($bodyBoxArr as $box) {
                $boxArr[] = [
                    'box_model_id' => $box['box_model_id'],
                    'cabinet_id' => $this->_cabinetId,
                    'body_id' => $body['body_id'],
                    'row' => $box['row'] ? : 1,
                    'column' => $box['column'] ? : 1,
                    'addr' => $box['addr'] ? : '00',
                    'create_time' => $now,
                ];
            }
        }

        $ret = D('CabinetBox')->addAll($boxArr);
        if($ret) {
            echo 'success init '.sizeof($boxArr).' box';
        } else {
            echo 'fail';
        }
        exit;

        //old function: init box data from box.json
        $file = '/app/unibox-en/web/box.json';
        $data = file_get_contents($file);
        $arr = json_decode($data, true);
        foreach($arr['cabinets'] as $body) {
            $bodyId = D('CabinetBody')->insertBody([
                'cabinet_id' => $this->_cabinetId,
                'body_model_id' => '10001',
                'addr' => $body['lockaddr'] ? : '00',
            ]);
            if($body['cabinet_type'] == 'sub') {
                foreach($body['box'] as $box) {
                    D('CabinetBox')->insertBox([
                        'cabinet_id' => $this->_cabinetId,
                        'body_id' => $bodyId,
                        'box_model_id' => '10001',
                        'addr' => $box['boxaddr'] ? : '00',
                        'row' => $box['row'] ? : 1,
                        'column' => $box['column'] ? : 1,
                    ]);
                }
            }
        }
    }

    public function getBoxConfig() {

        //header('Content-type: application/json');
        //$json = file_get_contents('box.json');
        //exit($json);

        $Box = D('CabinetBox');
        $cabinet = D('Cabinet')->getCabinet($this->_cabinetId);
        if (empty($cabinet)) { $this->ret(2);}

        $boxConfig = [
            'cabinetId' => $this->_cabinetId,
            'address' => $cabinet['address'],
            'zipcode' => $cabinet['zipcode'],
            'cabinets' => [],
        ];
        $bodysArr = D('CabinetBody')->getBodyList(['cabinet_id'=>$this->_cabinetId]);
        $boxModelArr = D('CabinetBoxModel')->getBoxModelConf(null, false);
        foreach($bodysArr as $b => $bodyArr) {
            $body = [
                'bodyId' => $bodyArr['body_id'],
                'cabinetType' => D('CabinetBodyModel')->getByModelId($bodyArr['body_model_id'])['model_name'],
                'model' => $bodyArr['body_model_id'],
                'lockAddr' => $bodyArr['addr'],
                'sequence' => $bodyArr['sequence'],
				'col'=> $bodyArr['col'],
            ];
            $boxesArr = $Box->getBoxList(['cabinet_id'=>$this->_cabinetId, 'body_id'=>$bodyArr['body_id']]);
            foreach($boxesArr as $x => $boxArr) {
                $body['boxes'][] = array_merge([
                    'boxId' => $boxArr['box_id'],
                    'boxAddr' => $boxArr['addr'],
                    'isAllocable' => $boxArr['is_allocable'] ? 1 : 0,
                    'row' => $boxArr['row'],
                    'column' => $boxArr['column'],
                    'blocked' => $boxArr['blocked'],
                    'model' => $boxArr['box_model_id'],
                ], $boxModelArr[$boxArr['box_model_id']]);
            }
            $boxConfig['cabinets'][] = $body;
        }
        $ret = ['boxConfig' => $boxConfig];
        $this->ret(0, $ret);
    }

    public function getAppQRUrl() {

        $QRCode = new \Common\Common\QRCode();
        $qrcode = $QRCode->buildQRCode('member');

        if (!$qrcode) { $this->ret(2);}

        $sceneId = $qrcode['text'];
        S(C('redis_config'))->proxy('RPUSH', $sceneId, $sceneId); //往队列里插入key，用于获取扫描结果时验证，以此实现一个二维码只能用一次
        S(C('redis_config'))->proxy('EXPIRE', $sceneId, 300);//设置场景过期时间
        $this->ret(0, [
            'sceneId'   => $sceneId,
            'url'       => $qrcode['url'],
        ]);
    }

    public function getAppScanResult() {

        set_time_limit(60);

        $sceneId = I('post.sceneId');
        if (!$sceneId) { $this->ret(2);}

        //if(1 == 1) {
        //    sleep(1);
        //    $memberId = 21001;
        //} else {

            $sid = S(C('redis_config'))->proxy('LPOP', $sceneId); //验证队列头部数据是否是场景ID，不是代表此二维码已经用过了
            if ($sid != $sceneId) { $this->ret(4);}

            $sceneMemberId = S(C('redis_config'))->proxy('BLPOP', $sceneId, 60); //阻塞式获取第二个数据，即等待用户扫描
            $data = !empty($sceneMemberId) ? json_decode($sceneMemberId[1], true) : array();
            $memberId = $data['memberId'];
            if (!isset($memberId) || empty($memberId)) { $this->ret(4);}
        //}

        $res['member']      = $this->getMember($memberId);

        $this->ret(0, $res);
    }

    public function getBoxModelList() {

        $boxModelArr = D('CabinetBoxModel')->getBoxModelConf($this->_cabinetId);
        $data = [
            'boxModelList' => array_values($boxModelArr),
        ];

        $this->ret(0, $data);
    }

    public function blockBox() {

        $boxId = I('post.boxId');

        if (empty($boxId)) {
            $this->ret(2);
        }

        if(D('CabinetBox')->blockBox($boxId, $this->_cabinetId)) {

            //send Email Notice
            $Email = new \Common\Common\Email();
            $Email->sendByPHPMailer([
                'mailfrom' => C('SMTP_USER_EMAIL'),
                'mailtoArr' => C('error_notice_email_list'),
                'subject' => 'Locker Error Report',
                'body' => "A Box is Blocked.<br>Locker Id: $this->_cabinetId <br>Box Id: $boxId",
            ]);

            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }
    public function releaseblockBox() {

        $boxId = I('post.boxId');

        if (empty($boxId)) {
            $this->ret(2);
        }
        if(D('CabinetBox')->releaseblockBox($boxId, $this->_cabinetId)) 
		{
            $this->ret(0);
        } else {
            $this->ret(3);
        }
    }
    public function initBoxStatus() {

        D('CabinetBox')->updateBoxStatus([
            'cabinet_id'=>I('request.id'),
        ]);

        D('OStore')->where([
            'cabinet_id'=>I('request.id'),
        ])->delete();

        D('ZDeliver')->where([
            'from_cabinet_id'=>I('request.id'),
        ])->delete();

        $ids = D('ZDeliver')->field('deliver_id')->where(['to_cabinet_id'=>I('request.id')])->select();
        foreach($ids as $id) {
            $deliverIdArr[] = $id['deliver_id'];
        }
        if($ids) {
            D('ZCourierOrder')->where([
                'deliver_id' => ['in', $deliverIdArr]
            ])->delete();
        }

        D('ZDeliver')->where([
            'to_cabinet_id'=>I('request.id'),
        ])->delete();

    }

    public function getMember($memberId) {

        return $this->formatOutput('member',
            D('Member')->getMemberById($memberId),
            D('MemberProfile')->getProfile($memberId),
            D('Wallet')->getWallet($memberId)
        );
    }

    public function getCourier($courierId) {

        return $this->formatOutput('courier',
            D('OCourier')->getCourier($courierId)
        );
    }
    public function getCourierCompanyApartment($courierId)
    {
   
        return $this->formatOutput('couriercompany',
            D('OCourierCompanyOrganization')->getCompanyCourier($courierId)
        
       );
    }       

}
