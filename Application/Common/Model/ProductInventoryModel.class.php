<?php
namespace Common\Model;
use Think\Model;

class ProductInventoryModel extends Model{
	protected $trueTableName = 'product_inventory';
    //插入记录
    public function insertMember($data){
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function updateStore($storeId, $data){
        return $this->data($data)->where("product_inventory_id=%d", $storeId)->save();
    }
    public function getProductInventoryList($wh = array()){
        return $this->where($wh)->select();
    }
	public function getListArr($wh = array()){
        $list = $this->getProductInventoryList($wh);
        foreach($list as $k => $c) {
            $arr[] = [
                'product_inventory_id' => $c['product_inventory_id'],
                'product_id' => $c['product_id'],
				'cabinet_id' => $c['cabinet_id'],
				'box_id' => $c['box_id'],
				'rfid' => $c['rfid'],
            ];
        }
        return $arr;
	
		//return $list;
    }
	public function getProductInventoryListN($wh = array()){
		$mem = $this
		    ->alias('t') 
            ->field('
                t.product_inventory_id,
				t.barcode,
				t.product_id,
				t.cabinet_id,
				t.organization_id,
				t.member_id,
                o.boxmodel_id,
                t.rfid,
				t.product_status_code,
                o.product_name as product_name,
                o.brand as brand,
                o.manufacturer as manufacturer,
                o.part_num as part_num,
                t.box_id as box_id,
				o.product_image,
				o.product_thumbnail
            ')
            ->join('left join product as o on t.product_id = o.product_id')
//            ->join('left join product_rental as b on t.product_inventory_id = b.product_inventory_id and b.rental_status_code=0')
            ->where($wh)->select();
         return $mem;
    }
	//加入ORDERABLE
	public function getProductInventoryListNOrder($wh = array()){
		$mem = $this
		    ->alias('t') 
            ->field('
                t.product_inventory_id,
				t.barcode,
				t.product_id,
				t.cabinet_id,
				t.organization_id,
				t.member_id,
                o.boxmodel_id,
                t.rfid,
				t.product_status_code,
                o.product_name as product_name,
                o.brand as brand,
				o.orderable as orderable,
                o.manufacturer as manufacturer,
                o.part_num as part_num,
                t.box_id as box_id,
				o.product_image,
				o.product_thumbnail
            ')
            ->join('left join product as o on t.product_id = o.product_id')
//            ->join('left join product_rental as b on t.product_inventory_id = b.product_inventory_id and b.rental_status_code=0')
            ->where($wh)->select();
         return $mem;
    }
	//
	//通过产品类别返回库存信息
	public function getProductInventoryListN1($wh = array()){
		$mem = $this
		    ->alias('t') 
            ->field('
                t.product_inventory_id,
				t.product_id,
				t.cabinet_id,
				t.organization_id,
				t.member_id,
                o.boxmodel_id,
                t.rfid,
				t.product_status_code,
                o.product_name as product_name,
                o.brand as brand,
                o.manufacturer as manufacturer,
                o.part_num as part_num,
                t.box_id as box_id,
				o.product_image,
				o.product_thumbnail
            ')
            ->join('join product as o on t.product_id = o.product_id')
            ->where($wh)->select();
         return $mem;
    }
    public function getProductInventoryArr($wh = array()){
        $list = $this->getProductInventoryListN($wh);
        foreach($list as $k => $c) {
			
            $arr[$c['product_id']] = [
			    'product_inventory_id' => $c['product_inventory_id'],
			    'product_id' => $c['product_id'],
                'cabinet_id' => $c['cabinet_id'],
				'organization_id' => $c['organization_id'],
				'member_id' => $c['member_id'],
				'boxmodel_id' => $c['boxmodel_id'],
                'rfid' => $c['rfid'],
				'barcode' => $c['barcode'],
				'product_status_code' => $c['product_status_code'],
                'product_name' => $c['product_name'],
                'brand' => $c['brand'],
                'manufacturer' => $c['manufacturer'],
                'box_id' => $c['box_id'],
                'part_num' => $c['part_num'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
            ];
        }
        return $arr;
    }
	//搜出可预定
	public function getProductInventoryArrOrder($wh = array()){
        $list = $this->getProductInventoryListNOrder($wh);
        foreach($list as $k => $c) {
			
            $arr[$c['product_id']] = [
			    'product_inventory_id' => $c['product_inventory_id'],
			    'product_id' => $c['product_id'],
                'cabinet_id' => $c['cabinet_id'],
				'organization_id' => $c['organization_id'],
				'member_id' => $c['member_id'],
				'boxmodel_id' => $c['boxmodel_id'],
                'rfid' => $c['rfid'],
				'barcode' => $c['barcode'],
				'product_status_code' => $c['product_status_code'],
                'product_name' => $c['product_name'],
                'brand' => $c['brand'],
                'manufacturer' => $c['manufacturer'],
                'box_id' => $c['box_id'],
                'part_num' => $c['part_num'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
				'orderable' => $c['orderable'],
            ];
        }
        return $arr;
    }
	//
	public function getProductInventoryArr1($wh = array(),$memberid){
        $list = $this->getProductInventoryListN1($wh);
        foreach($list as $k => $c) {
			$wh1['product_id'] = $c['product_id'];
            $unitArr = D('Product')->getMember($wh1);
            //此处判断产品是否公开
		    if($unitArr['is_public']=='0')
		    {
			  //查授权
			  $wh2=
		      [
		      'product_id'=>$c['product_id'] ,
		      'member_id' => $memberid,
			  'approve_status' => '1',
		      ];
			  $ProductAuth = D('ProductAuth')->getMember($wh2);
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
		    //公开
	        { 
		      $showf='1';
		    }
			//
		    if($showf=='1')
		    {
             $arr[$c['product_id']] = [
			    'product_inventory_id' => $c['product_inventory_id'],
			    'product_id' => $c['product_id'],
                'cabinet_id' => $c['cabinet_id'],
				'organization_id' => $c['organization_id'],
				'member_id' => $c['member_id'],
				'boxmodel_id' => $c['boxmodel_id'],
                'rfid' => $c['rfid'],
                'product_name' => $c['product_name'],
                'brand' => $c['brand'],
                'manufacturer' => $c['manufacturer'],
                'box_id' => $c['box_id'],
                'part_num' => $c['part_num'],
				'product_image' => $c['product_image'],
				'product_thumbnail' => $c['product_thumbnail'],
              ];
			}
        }
        return $arr;
    }
    //租借列表
    public function getStatementListN($wh = array()){
		$mem = $this
		    ->alias('t') 
            ->field('
			    o.product_name as product_name,
			    o.brand as brand,
				t.update_time as rental_time
            ')
            ->join('join product as o on t.product_id = o.product_id')
            ->where($wh)->select();
		return $mem;
		//
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
        return $this->where($wh)->order('product_inventory_id')->select();
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
