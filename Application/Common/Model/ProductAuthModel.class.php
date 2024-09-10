<?php
namespace Common\Model;
use Think\Model;

class ProductAuthModel extends Model{
	protected $trueTableName = 'o_product_auth';
    //插入记录
    public function insertMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
	//得到记录（1条）
	public function getMember($wh){
        return $this->where($wh)->find();
    }
	//得到记录（多条）
	public function getList($where = array())
	{
        return $this->where($where)->select();
    }
	//得到记录（多条关键字检索）
    public function getMemberList($wh){
        return $this->where($wh)->order('rental_id')->select();
    }
	//删除记录
	public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	//更新记录
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
		
}
