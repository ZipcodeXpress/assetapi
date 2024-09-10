<?php

namespace Think\Log\Driver;

class Logger {

    private $config  =  null;
    private $logListKey = 'logger_server_list_key';

    // 实例化并传入参数
    public function __construct($config=array()){
        $this->config = array(
            'log_time_format'   =>  C('UNIBOX_LOG_TIME_FORMAT') ? C('UNIBOX_LOG_TIME_FORMAT') : 'Y-m-d H:i:s',
            'log_path'          =>  C('UNIBOX_LOG_PATH') ? C('UNIBOX_LOG_PATH') : '/data1/logs/weblog/unibox/',
            'log_file_name'     =>  C('UNIBOX_LOG_FILE_NAME') ? C('UNIBOX_LOG_FILE_NAME') : 'unibox',
        );
        if (is_string($config)) {
            $this->config['log_file_name'] = $config;
        } else{
            $this->config   =   array_merge($this->config,$config);
        }
    }

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($log,$destination='') {
        if (is_array($log)) {
            $log = json_encode($log, JSON_UNESCAPED_SLASHES);
            $log = htmlspecialchars_decode($log);
        }
        $now = date($this->config['log_time_format']);
        if(empty($destination)){
            $destination = $this->config['log_path'].date('Ymd').'/'.$this->config['log_file_name'].'.log';
        }
        $module = defined('MODULE_NAME') ? MODULE_NAME : '';
        $controller = defined('CONTROLLER_NAME') ? CONTROLLER_NAME : '';
        $action = defined('ACTION_NAME') ? ACTION_NAME : '';
        $data = array(
            'file' => $destination,
            'content' => "[{$now}]    ".$_SERVER['REMOTE_ADDR']."    module:".$module."    controller:".$controller."    action:".$action."    {$log}",
        );

        S(C('redis_config'))->proxy('RPUSH', $this->logListKey, json_encode($data));
    }
}
