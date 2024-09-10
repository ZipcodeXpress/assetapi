<?php
namespace Opr\Controller;
use Common\Common;
use Think\Controller;

class BaseController extends Controller {

    protected $_memberId = null;
    static $_log = null;

    protected function ret($ret = 0, $data=NULL, $msg=NULL) {

        if(is_array($ret)) { $this->ajaxReturn($ret);}

        $msg = $msg ? : L(strtolower(CONTROLLER_NAME).'.'.strtolower(ACTION_NAME).'.'.$ret);

        self::$_log->write([
            'method' => REQUEST_METHOD,
            'input'  => REQUEST_METHOD == 'POST' ? I('post.') : I('get.'),
            'output' => [
                'ret' => $ret,
                'msg' => $msg,
                'data' => strlen(json_encode($data)) < 2000 ? $data : 'data is too long to show'
            ]
        ]);

        $this->ajaxReturn([
            'ret' => $ret,
            'msg' => ucfirst($msg).($ret == 0 ? '' : '!'),
            'data' => $data
        ]);
    }

    public function __construct(){
        parent::__construct();

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('opr');
        }

        if (!in_array(CONTROLLER_NAME, ['VCode', 'Barcode', 'MessageSms']) && !in_array(ACTION_NAME, ['version', 'show'])) {

            $Login = new \Common\Common\Login();
            $this->_memberId = $Login->getLoginUser();
            $this->_memberId = intval($this->_memberId);

            if (!in_array(CONTROLLER_NAME, ['Login'])) {
                if(empty($this->_memberId)) { $this->ret(1);}
            }
        }

        if($_SERVER['HTTP_X_FROM'] == 'app') {
            define('IS_APP', true);
            define('IS_WEB', false);
        } else {
            define('IS_APP', false);
            define('IS_WEB', true);
        }

        self::$_log->write([
            'method' => REQUEST_METHOD,
            'input'  => REQUEST_METHOD == 'POST' ? I('post.') : I('get.'),
            'http_x_from' => $_SERVER['HTTP_X_FROM'],
            'is_web' => IS_WEB,
            'is_app' => IS_APP,
        ]);
    }
}
