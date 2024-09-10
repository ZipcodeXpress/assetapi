<?php
namespace Common\Model;
use Think\Model;
use Think\Log\Driver;

class AuthitemModel extends Model{

    protected $trueTableName = 'auth_item';
/* 
   插入用户信息
     * @params    phone    用户账号
     * @params    psd      用户密码
     * @return */
   
/*     public function insertMember($item_name, $user_id){
        $now = time();
        $data = array(
            'item_name'=>$item_name,
            'user_id'=>$user_id,
            'created_at'=>$now,
        );
        if ($this->create($data, self::MODEL_INSERT)) {
            return $this->add();
        } else{
            return FALSE;
        }
    } */
	
    public function insertRecord($data){
        $now = time();
        if ($this->create($data, self::MODEL_INSERT)) {
            return $this->add();
        } else{
            return FALSE;
        }
    }

    /* 根据memberid获取用户信息
     * @params    phone    用户账号
     * @params    field    信息字段，默认取全部
     * @return
    */
    public function getMemberById($user_id, $field='*'){
        return $this->field($field)->where(array('name'=>$user_id))->find();
    }

    public function getList($wh){
        return $this->where($wh)->order('created_at')->select();
    }

    /* 更加memberid更新用户信息
     * @params    phone    用户账号
     * @params    data     更新数据
     * @return
    */
    public function updateMemberById($memberId, $data){
        return $this->where(array('name'=>$memberId))->data($data)->save();
    }


    public function deleteMemberById($memberId){
        return $this->where('name = "%s"', $memberId)->delete();
    }
}
