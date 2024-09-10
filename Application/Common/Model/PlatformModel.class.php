<?php
namespace Common\Model;
use Think\Model;

class PlatformModel extends Model{

    public function getInfo(){
        return $this->select();
    }

}
