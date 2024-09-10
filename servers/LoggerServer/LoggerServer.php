<?php
ini_set('default_socket_timeout', -1);

date_default_timezone_set('Asia/Shanghai');

$conf = array(
    'dev' => array('ip'=>'127.0.0.1','port'=>6379),
    'product' => array('ip'=>'172.22.0.2', 'port'=>6379),
);

$env = $argv[1];
if (empty($env) || !isset($conf[$env])) {
    exit('请传入环境参数， dev 或者 product');
}
$Redis = new Redis();
$Redis->pconnect($conf[$env]['ip'], $conf[$env]['port']);

$key = 'logger_server_list_key';
$handle = array();
while($data = $Redis->BLPOP($key, 0)){
    $json = empty($data) ? array() : json_decode($data[1], true);
    if (empty($json)) {
        continue;
    }

    $pathArr = pathinfo($json['file']);
    if (!is_dir($pathArr['dirname'])) {
        umask(0);
        mkdir($pathArr['dirname'], 0774);
    }

    $now = time();
    if (!isset($handle[$json['file']])) {
        $file = fopen($json['file'], 'a+');
        $handle[$json['file']] = array('f'=>$file, 'time'=>$now);
    }

    fwrite($handle[$json['file']]['f'], $json['content'] . "\n");

    foreach ($handle as $k => $v) {
        if ($now - $v['time'] > 86400) {
            fclose($v['f']);
            unset($handle[$k]);
        }
    }
}
