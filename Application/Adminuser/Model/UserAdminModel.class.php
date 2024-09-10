<?php
namespace Adminuser\Model;
use Think\Model;

class UserAdminModel extends BaseAdminModel{
    const USER_PASSWORD_KEY = 'unibox_!@#$%_UNIBOX_^&*()';
    protected $trueTableName = 't_user';

    public function getPsd($password){
        return md5(self::USER_PASSWORD_KEY . $password);
    }

    public function getUserInfoById($id){
        return $this->where(array('id'=>$id))->find();
    }

    public function getUserList(){
        return $this->select();
    }

    public function getUserInfoByName($name){
        return $this->where(array('name'=>$name))->find();
    }

    public function insertUserInfo($name, $password, $role, $desc = ''){
        $data = array(
            'name' => $name,
            'password' => $this->getPsd($password),
            'role' => $role,
            'status' => 0,
            'desc' => $desc,
        );
        return $this->data($data)->add();
    }

    public function updateUserInfo($id, $data){
        return $this->data($data)->where(array('id'=>$id))->save();
    }

    public function deleteUser($id){
        return $this->where(array('id'=>$id))->delete();
    }

    public function getKioskScope($id) {
        $kioskIds = $this->getFieldById($id, 'kiosk_ids');
        //return $kioskIds ? ['kiosk_id' => ['in', explode(',', $kioskIds)]] : [];
        return $kioskIds ? explode(',', $kioskIds) : [];
    }
}
