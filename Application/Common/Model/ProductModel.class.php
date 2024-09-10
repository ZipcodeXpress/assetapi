<?php
namespace Common\Model;
use Think\Model;

class ProductModel extends Model{
	protected $trueTableName = 'product';
    //插入记录
    public function insertMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
	
	public function getProductList($wh = array()){
        return $this->where($wh)->select();
    }
	
    public function getProductArr($wh = array()){
        $list = $this->getProductList($wh);
		/*
        foreach($list as $k => $c) {
            $arr[$c['product_id']] = [
                'product_id' => $c['product_id'],
                'product_name' => $c['product_name'],
				'organization_id' => $c['organization_id'],
				'boxmodel_id' => $c['boxmodel_id'],
				'category_id' => $c['category_id'],
				'brand' => $c['brand'],
				'manufacturer' => $c['manufacturer'],
            ];
        }
        return $arr;
		*/
		return $list;
    }
	public function getProductArrN($wh = array()){
        $list = $this->getProductList($wh);

        foreach($list as $k => $c) {
			
            $arr[$c['product_id']] = [
			    'product_id' => $c['product_id'],
                'product_name' => $c['product_name'],
				'category_id' => $c['category_id'],
				'boxmodel_id' => $c['boxmodel_id'],
				'brand' => $c['brand'],
				'manufacturer' => $c['manufacturer'],
				'uom' => $c['uom'],
				'part_num' => $c['part_num'],
				'model_num' => $c['model_num'],
				'is_public' => $c['is_public'],
				'product_desc' => $c['product_desc'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
				'instruction' => $c['instruction'],
				'create_time' => $c['create_time'],
				'update_time' => $c['update_time'],
				'end_date' => $c['end_date'],
            ];
        }
        return $arr;

    }
	//产品列表
    public function getProductArrN1($wh = array(),$memberid){
        $list = $this->getProductList($wh);
       
		
        foreach($list as $k => $c) {
		  //此处判断产品是否公开
		  if($c['is_public']=='0')
		  {
			//查授权
			$wh=
		    [
		      'product_id'=>$c['product_id'] ,
		      'member_id' => $memberid,
			  'approve_status' => '1',
		    ];
			$ProductAuth = D('ProductAuth')->getMember($wh);
			if(empty($ProductAuth))
			{
				$showf='0';
			}
			else 
			{
				$showf='1';
			}
		  }
		  else 
		  //全公开
	      { 
		      $showf='1';
		  }
		  if($showf=='1')
		  {
			$wh=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '1',
		    ];
			$unitArr = D('ProductInventory')->getMember($wh);	
            //			
		    $wh1=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '0',
		    ];
		    $wh2=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '1',
		    ];
	        $wh3=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '2',
		    ];
		    $wh4=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '3',
		    ];
	        $wh5['_complex'] = array(
              $wh1,
              $wh2,
			  $wh3,
              $wh4,
              '_logic' => 'or'
            ); 
			$unitArr1 = D('ProductInventory')->getMember($wh5);
            $arr[$c['product_id']] = [
                'product_id' => $c['product_id'],
                'product_name' => $c['product_name'],
				'category_id' => $c['category_id'],
				'boxmodel_id' => $c['boxmodel_id'],
				'brand' => $c['brand'],
				'manufacturer' => $c['manufacturer'],
				'uom' => $c['uom'],
				'part_num' => $c['part_num'],
				'model_num' => $c['model_num'],
				'is_public' => $c['is_public'],
				'product_desc' => $c['product_desc'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
				'instruction' => $c['instruction'],
				'create_time' => $c['create_time'],
				//'update_time' => $c['update_time'],
				//'end_date' => $c['end_date'],
				'cost' => $c['cost'],
				'consumable' => $c['consumable'],
				'available' => empty($unitArr) ? 1: 0,
				'delivered' => empty($unitArr1) ? 1 : 0,
				'orderable' => $c['orderable'],
				'box_id' =>empty($unitArr) ? '0': $unitArr['box_id'],
				'rfid' =>empty($unitArr) ? '0': $unitArr['rfid'],
            ];
		  }
		  /*
		  else
		  {
			  if($c['orderable']=='1')
			  {
				 $arr[$c['product_id']] = [
                'product_id' => $c['product_id'],
                'product_name' => $c['product_name'],
				'category_id' => $c['category_id'],
				'boxmodel_id' => $c['boxmodel_id'],
				'brand' => $c['brand'],
				'manufacturer' => $c['manufacturer'],
				'uom' => $c['uom'],
				'part_num' => $c['part_num'],
				'model_num' => $c['model_num'],
				'is_public' => $c['is_public'],
				'product_desc' => $c['product_desc'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
				'instruction' => $c['instruction'],
				'create_time' => $c['create_time'],
				//'update_time' => $c['update_time'],
				//'end_date' => $c['end_date'],
				'cost' => $c['cost'],
				'consumable' => $c['consumable'],
				'available' => empty($unitArr) ? 1 : 0,
				'delivered' => empty($unitArr1) ? 1 : 0,
				'orderable' =>1,
				'box_id' =>0,
				'rfid' =>'',
               ];
			  }	  
		  }
		  */
        }
        return $arr;

    }
	public function getProductArrN2($wh = array()){
        $list = $this->getProductList($wh);

        foreach($list as $k => $c) {
			$wh=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '1',
		    ];
			$unitArr = D('ProductInventory')->getMember($wh);
            $arr[$c['product_id']] = [
                'product_id' => $c['product_id'],
				'organization_id' => $c['organization_id'],
                'product_name' => $c['product_name'],
				'category_id' => $c['category_id'],
				'boxmodel_id' => $c['boxmodel_id'],
				'brand' => $c['brand'],
				'manufacturer' => $c['manufacturer'],
				'uom' => $c['uom'],
				'part_num' => $c['part_num'],
				'model_num' => $c['model_num'],
				'is_public' => $c['is_public'],
				'product_desc' => $c['product_desc'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
				'instruction' => $c['instruction'],
				'create_time' => $c['create_time'],
				'update_time' => $c['update_time'],
				'end_date' => $c['end_date'],
				'cost' => $c['cost'],
				'consumable' => $c['consumable'],
				'available' => empty($unitArr) ? 1 : 0,
            ];
        }
        return $arr;

    }
    public function getProductFormat($wh = array()){
        $list = $this->getMember($wh);
/* 		$wh=
		    [
		      'product_id'=>$c['product_id'] ,
		      'product_status_code' => '1',
		    ];
	    $unitArr = D('ProductInventory')->getMember($wh);
		$arr=array(
                'product_id' => $list['product_id'],
				'organization_id' => $list['organization_id'],
                'product_name' => $list['product_name'],
				'category_id' => $list['category_id'],
				'boxmodel_id' =>$list['boxmodel_id'],
				'brand' => $list['brand'],
				'manufacturer' => $list['manufacturer'],
				'uom' => $list['uom'],
				'part_num' => $list['part_num'],
				'model_num' => $list['model_num'],
				'is_public' => $list['is_public'],
				'product_desc' => $list['product_desc'],
				'product_image' => $list['product_image'],
				'product_thumbnail' => $list['product_thumbnail'],
				'instruction' => $list['instruction'],
				'create_time' => $list['create_time'],
				'update_time' => $list['update_time'],
				'end_date' => $list['end_date'],
				'available' => empty($unitArr) ? 1 : 0,
				) */
        return $list;

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
        return $this->where($wh)->order('product_id')->select();
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
