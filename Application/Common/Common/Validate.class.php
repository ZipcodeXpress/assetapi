<?php
namespace Common\Common;
class Validate{
    /* 输入数据校验类
     * 规则
     * @required : 要求是否存在                     eg : array('required'=>1)
     * @number : 要求是数字，true表示要求，         eg : array('number'=>1)
     * @max : 最大值限制, 需要和number一起使用      eg : array('number'=>1,'max'=>100)
     * @min : 最小值限制, 需要和number一起使用      eg : array('number'=>1,'min'=>100)
     * @maxLenght : 最大长度限制                    eg : array('maxLenght'=>1000)
     * @minLenght : 最小长度限制                    eg : array('minLenght'=>1000)
     * @inArray : 要求是在数组中                    eg : array('inArray'=>array(0,1,2))
    */
    private $_errMsg = '';

    private function setErrMsg($msg){
        $this->_errMsg = $msg;
    }

    public function getErrMsg(){
        return $this->_errMsg;
    }

    private function validate($value, $rule, $key){
        foreach ($rule as $k => $v) {
            switch ($k) {
                case 'number':
                    if ($v && !is_numeric($value)) {
                        $this->setErrMsg(sprintf('参数%s 要求是数字', $key));
                        return false;
                    }
                    if (isset($rule['max']) && $value > $rule['max']) {
                        $this->setErrMsg(sprintf('参数%s 过大，要求不得大于%d', $key, $rule['max']));
                        return false;
                    }
                    if (isset($rule['min']) && $value < $rule['min']) {
                        $this->setErrMsg(sprintf('参数%s 过小，要求不得小于%d', $key, $rule['min']));
                        return false;
                    }
                    break;
                case 'maxLenght':
                    if (mb_strlen($value) > $v) {
                        $this->setErrMsg(sprintf('参数%s 过长，要求不得多于%d个字符', $key, $v));
                        return false;
                    }
                    break;
                case 'minLenght':
                    if (mb_strlen($value) < $v) {
                        $this->setErrMsg(sprintf('参数%s 过短，要求不得少于%d个字符', $key, $v));
                        return false;
                    }
                    break;
                case 'inArray':
                    if (is_array($v) && !in_array($value, $v)) {
                        $this->setErrMsg(sprintf('参数%s 不合法', $key));
                        return false;
                    }
                    break;
                case 'url':
                    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                        $this->setErrMsg(sprintf('movie clip is not valid url', $key));
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        return true;
    }

    public function check($rule, $data = array()){
        if (empty($data)) {
            $data = $_POST;
        }

        foreach ($rule as $key => $value) {
            if (!isset($data[$key])) {
                if ($value['required']) {
                    $this->setErrMsg('缺少参数:' . $key);
                    return false;
                } else{
                    continue;
                }
            }
            $d = is_array($data[$key]) ? $data[$key] : array($data[$key]);
            foreach ($d as $v) {
                $ret = $this->validate($v, $value, $key);
                if (empty($ret)) {
                    return false;
                }
            }
        }
        return true;
    }
}
