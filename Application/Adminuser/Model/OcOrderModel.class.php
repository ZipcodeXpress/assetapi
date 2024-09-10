<?php
namespace Adminuser\Model;
use Think\Model;

class OcOrderModel extends Model{

    protected $trueTableName = 'ztopencart_order';

    public function getOrder($wh=[]){
        return $this->where($wh)->find();
    }

    public function updateOrder($wh=[], $data=[]){
        return $this->where($wh)->data($data)->save();
    }
    public function insertMember($data){
       if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function getBodyList($where = array()){
        return $this->where($where)->select();
    }
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('order_id')->select();
    }
	
	public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
	
    public function getCabinetList($where = array()){
        return $this->where($where)->select();
    }
}
