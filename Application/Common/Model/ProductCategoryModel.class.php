<?php
namespace Common\Model;
use Think\Model;

class ProductCategoryModel extends Model{
	protected $trueTableName = 'product_category';
    //插入记录
    public function insertMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
	public function getProductCategoryList($wh = array()){
        return $this->where($wh)->select();
    }
	
    public function getProductCategoryArr($wh = array()){
        $list = $this->getProductCategoryList($wh);
/*         foreach($list as $k => $c) {
            $arr[$c['product_cate_id']] = [
                'product_cate_id' => $c['product_cate_id'],
                'organization_id' => $c['organization_id'],
				'product_cate_name' => $c['product_cate_name'],
				'product_cate_desc' => $c['product_cate_desc'],

            ];
        } */
		return $list;
    }
	public function getProductCategoryArrN($wh = array()){
        $list = $this->getProductCategoryList($wh);
        foreach($list as $k => $c) {
            $arr[$c['product_cate_id']] = [
                'product_cate_id' => $c['product_cate_id'],
                'organization_id' => $c['organization_id'],
				'product_cate_name' => $c['product_cate_name'],
				'product_cate_desc' => $c['product_cate_desc'],

            ];
        }
		return $arr;
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
        return $this->where($wh)->order('product_cate_id')->select();
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
