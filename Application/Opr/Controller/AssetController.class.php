<?php
namespace Opr\Controller;
use Think\Controller;

class AssetController extends BaseController 
{
	//通过MEMBERid找RFID信息 （资产柜新加）   
	 /**
	 * @api {get} /asset/identifyCardForRent 01-identifyCardForRent
     * @apiDescription 识别租借人身份通过ID卡
     * @apiName identifyCardForRent
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                                     
            '1' => 'invalid accesstoken',                                                                              
            '3' => 'no matched member found',                                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.rfid
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "member": {
     *             "memberId": "10001",
     *             "rfid": "123"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function identifyCardForRent() {
         /*
        $cardCode = I('request.cardCode');
        if(empty($cardCode))
	    {
           $this->ret(2);
        } 
		$wh['cardcode'] = $cardCode;
		*/
		$wh['member_id'] = $this->_memberId;
        $courier = D('Member')->getMember($wh);
         if($courier) {
			//$res['member']      = $courier;
            $res['member']['member_id']      = $courier['member_id'];
			$res['member']['rfid']      = $courier['cardcode'];
        } else {
            $this->ret(3);
        }
        $this->ret(0, $res);
    } 
	 /**资产柜借WEB端提交
	 * @api {get} /asset/commitForAssetRent 02-commitForAssetRent
     * @apiDescription 借阅提交WEB端
     * @apiName commitForAssetRent
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId 
     * @apiParam {String} productId           产品id
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                      
            '2' => 'empty organization id',                           
            '3' => 'empty inventory id',                       
            '5' => 'empty product id',	
            '6' => 'empty box id',	
            '8' => 'update rental table fail',	
            '9' => 'update inventory table fail',	
            '10' => 'no the product',				
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.rent
     * @apiSuccess {String}   data.rent.rentalId
     * @apiSuccess {String}   data.rent.memberId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitForAssetRent Success",
     *     "data": {
     *         "rent": {
     *             "rentalIdId": "10007",
     *             "memberId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitForAssetRent() 
	{
        $rfId    = I('request.productId');
        if(empty($rfId ))  { $this->ret(5);}
		//
	    $wh=[
		'product_id'=>$rfId,
		'product_status_code'=>'1',
		];
		$productinfo =D('ProductInventory')->getMember($wh);
	    if(empty($productinfo)) { $this->ret(10);}
		//
        $now = time();
        $holdTime = 86400;
        $pickCode = \Org\Util\String::randString(6, 2);
        $rental = array(
            //'rental_id'
			'organization_id' => $productinfo['organization_id'],
            'cabinet_id' => $productinfo['cabinet_id'],
			//'member_id' =>$memberId,
            'product_inventory_id' => $productinfo['product_inventory_id'],
			'box_id' => $productinfo['box_id'],
			'rfid' => $productinfo['rfid'],
            'member_id' => $this->_memberId,
			'pickup_code' =>$pickCode,
            //'expire_time' => $now + $holdTime,
            'reserve_time' => $now,
			'rental_status_code'=>'2',
        );

        $boxId = D('ProductRental')->insertMember($rental);
        if(empty($boxId))       { $this->ret(6);}	
		//发邮件
	    // async send notice
		/*
        S(C('redis_config'))->proxy('RPUSH', 'async_notice', json_encode([
            'notice_tpl' => C('NOTICE.NT_ASSET_HAS_PACKAGE_TO_PICK'),
            'member_id' =>$this->_memberId,
            'data' => [
                'cabinet_id' => $productinfo['cabinet_id'],
                'pick_code' => $pickCode,
            ]
        ]));	
		*/
        //SMS notice && Email notice
        $Notice = new \Common\Common\Notice();
        $Notice->notice(C('NOTICE.NT_ASSET_HAS_PACKAGE_TO_PICK'), $this->_memberId, [
            //'cabinet_id' => $this->_cabinetId,
		    'cabinet_id' => $productinfo['cabinet_id'],
            'pick_code' => $pickCode,  
        ]);
		//更新rental状态	
        $wh=['rfid'=>$productinfo['rfid'],];
		//更新INVENTORY状态
        $store = [
		    'member_id'=>$this->_memberId,
            'update_time' => $now,
            'product_status_code' => '2',
            //'box_id'=>'0',预定不用更新BOXID
        ];
        $storeId = D('ProductInventory')->updateMember($wh,$store);
        if(empty( $storeId))       { $this->ret(9);}

        $this->ret(0, [
            'rent' => $rental,
        ]);
    }	
	 /**得到库存产品列表（资产柜新加）
	 * @api {get} /asset/getProductInventoryList 03-getProductInventoryList
     * @apiDescription 得到库存产品列表
     * @apiName getProductInventoryList
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId 
	 * @apiParam {String} cabinetId 快递柜ID号
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',  
            '2' => 'invalid cabinet id', 			
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.product_inventory_id
     * @apiSuccess {String}     data.productList.product.product_id
	 * @apiSuccess {String}     data.productList.product.cabinet_id
     * @apiSuccess {String}     data.productList.product.organization_id
     * @apiSuccess {String}     data.productList.product.boxmodel_id
     * @apiSuccess {String}     data.productList.product.rfid
     * @apiSuccess {String}     data.productList.product.product_status_code
	 * @apiSuccess {String}     data.productList.product.product_name
     * @apiSuccess {String}     data.productList.product.brand
     * @apiSuccess {String}     data.productList.product.manufacturer
     * @apiSuccess {String}     data.productList.product.box_id
	 * @apiSuccess {String}     data.productList.product.part_num
     * @apiSuccess {String}     data.productList.product.product_image	 
	 * @apiSuccess {String}     data.productList.product_thumbnail	
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productInventoryList": [
     *             {
     *              "product_inventory_id": "6",
     *              "product_id": "202",
     *              "cabinet_id": "10107",
     *              "organization_id": "666",
     *              "boxmodel_id": "10001",
     *              "rfid": "1111",
     *              "product_status_code": "1",	 
     *              "product_name": "samsung camera23",
     *              "brand": "samsung",
     *              "manufacturer": "",
     *              "box_id": "10760",
     *              "part_num": null,
     *              "product_image": "uploads/_LZ2tv1Iu3WU8UKn-PETH8iwVjYvx_2l.jpg",
     *              "product_thumbnail": "uploads/thumb/_LZ2tv1Iu3WU8UKn-PETH8iwVjYvx_2l.jpg"
     *             },
     *             {
     *               "product_inventory_id": "7",
     *               "product_id": "203",
     *               "cabinet_id": "10107",
     *               "organization_id": "666",
     *               "boxmodel_id": "10001",
     *               "rfid": "1112",
     *               "product_status_code": "1",	 
     *               "product_name": "samsung camera2345",
     *               "brand": "",
     *               "manufacturer": "31313",
     *               "box_id": "10771",
     *               "part_num": null,
     *               "product_image": "uploads/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *               "product_thumbnail": "uploads/thumb/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg"
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductInventoryList() {
  
          
        $cabinetId = I('request.cabinetId');

        if(empty($cabinetId)){
            $this->ret(2);
        }
		//
		$wh1=[
		        't.cabinet_id'=>$cabinetId ,
		        't.product_status_code' => '1',
                ];
		$wh2=[
		        't.cabinet_id'=>$cabinetId ,
		        't.product_status_code' => '3',
                ];	
	    $wh['_complex'] = array(
              $wh1,
              $wh2,
              '_logic' => 'or'
            ); 
		//
        $unitArr = D('ProductInventory')->getProductInventoryArr($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productInventoryList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }
	/**得到产品种类列表（资产柜新加）
	 * @api {get} /asset/getProductCategoryList 04-getProductCategoryList
     * @apiDescription 得到产品种类列表
     * @apiName getProductCategoryList
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId 
	 * @apiParam {String} organizationId 所有者ID号
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get productcategory list success',                      
            '1' => 'invalid accesstoken',  
            '2' => 'invalid organization id', 			
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.product_cate_id
     * @apiSuccess {String}     data.productList.product.organization_id
     * @apiSuccess {String}     data.productList.product.boxmodel_id
     * @apiSuccess {String}     data.productList.product.product_cate_name
	 * @apiSuccess {String}     data.productList.product.product_cate_desc
     * @apiSuccess {String}     data.productList.product.create_time
     * @apiSuccess {String}     data.productList.product.update_time
     * @apiSuccess {String}     data.productList.product.box_id
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Productcategory List Success",
     *     "data": {
     *         "productcategoryList": [
     *             {
     *              "product_cate_id": "1",
     *              "organization_id": "666",
     *              "boxmodel_id": "10001",
     *              "product_cate_name": "aa",
     *              "product_cate_desc": "no",
     *              "create_time": "1562660000",
     *              "update_time": null
     *             },
     *             {
     *              "product_cate_id": "2",
     *              "organization_id": "666",
     *              "boxmodel_id": "10001",
     *              "product_cate_name": "aa",
     *              "product_cate_desc": "no",
     *              "create_time": "1562660001",
     *              "update_time": null
     *             }
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductCategoryList() {
  
          
        $apartmentId = I('request.organizationId');

        if(empty($apartmentId)){
            $this->ret(2);
        }
		$wh['organization_id'] = $apartmentId;
        $unitArr = D('ProductCategory')->getProductCategoryArr($wh);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productcategoryList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }
	 /**得到产品列表（资产柜新加）
	 * @api {get} /asset/getProductList 05-getProductList
     * @apiDescription 得到产品列表
     * @apiName getProductList
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId 
	 * @apiParam {String} organizationId 所有者ID号
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'get product list success',                      
            '1' => 'invalid accesstoken',  
            '2' => 'invalid organization id', 			
            '3' => 'no product', 			
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.product_id
     * @apiSuccess {String}     data.productList.product.organization_id
	 * @apiSuccess {String}     data.productList.product.product_name
     * @apiSuccess {String}     data.productList.product.category_id
	 * @apiSuccess {String}     data.productList.product.boxmodel_id
     * @apiSuccess {String}     data.productList.product.brand
	 * @apiSuccess {String}     data.productList.product.manufacturer
     * @apiSuccess {String}     data.productList.product.uom
	 * @apiSuccess {String}     data.productList.product.part_num
     * @apiSuccess {String}     data.productList.product.model_num
	 * @apiSuccess {String}     data.productList.product.is_public
     * @apiSuccess {String}     data.productList.product.product_desc
	 * @apiSuccess {String}     data.productList.product.product_image
     * @apiSuccess {String}     data.productList.product.product_thumbnail
	 * @apiSuccess {String}     data.productList.product.create_time
     * @apiSuccess {String}     data.productList.product.update_time
	 * @apiSuccess {String}     data.productList.product.end_date
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productList": [
     *             {
     *              "product_id": "203",
     *              "organization_id": "10002",
     *              "product_name": "samsung camera2345",
     *              "category_id": "1",
     *              "boxmodel_id": "10001",
     *              "brand": "",
     *              "manufacturer": "31313",
     *              "uom": "",
     *              "part_num": null,
     *              "model_num": null,
     *              "is_public": null,
     *              "product_desc": "213123",
     *              "product_image": "uploads/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *              "product_thumbnail": "uploads/thumb/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *              "instruction": "",
     *              "create_time": "1563069062",
     *              "update_time": "1563165281",
     *              "end_date": null,
	 *              "available": 1
     *             },
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductList() {
  
          
        $apartmentId = I('request.organizationId');

        if(empty($apartmentId)){
            $this->ret(2);
        }
		$wh['organization_id'] = $apartmentId;
        $unitArr = D('Product')->getProductArrN1($wh,$this->_memberId);
		if(empty($unitArr)){
            $this->ret(3);
        }
        $data = [
            'productList' => array_values($unitArr),
        ];

        $this->ret(0, $data);
    }
	 /**得到产品详细信息（资产柜新加）
	 * @api {get} /asset/getProductInfo 06-getProductInfo
     * @apiDescription 得到产品列表
     * @apiName getProductInfo
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId 
	 * @apiParam {String} productId      产品ID
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',                      
            '1' => 'invalid accesstoken',  
            '2' => 'empty product id',                                      	
            '5' => 'product not exist',		
     * @apiSuccess {Object} data
     * @apiSuccess {Object[]} data.productList
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {Object}     data.productList.product
     * @apiSuccess {String}     data.productList.product.product_id
     * @apiSuccess {String}     data.productList.product.organization_id
	 * @apiSuccess {String}     data.productList.product.product_name
     * @apiSuccess {String}     data.productList.product.category_id
	 * @apiSuccess {String}     data.productList.product.boxmodel_id
     * @apiSuccess {String}     data.productList.product.brand
	 * @apiSuccess {String}     data.productList.product.manufacturer
     * @apiSuccess {String}     data.productList.product.uom
	 * @apiSuccess {String}     data.productList.product.part_num
     * @apiSuccess {String}     data.productList.product.model_num
	 * @apiSuccess {String}     data.productList.product.is_public
     * @apiSuccess {String}     data.productList.product.product_desc
	 * @apiSuccess {String}     data.productList.product.product_image
     * @apiSuccess {String}     data.productList.product.product_thumbnail
	 * @apiSuccess {String}     data.productList.product.create_time
     * @apiSuccess {String}     data.productList.product.update_time
	 * @apiSuccess {String}     data.productList.product.end_date
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Get Product List Success",
     *     "data": {
     *         "productList": [
     *             {
     *              "product_id": "203",
     *              "organization_id": "10002",
     *              "product_name": "samsung camera2345",
     *              "category_id": "1",
     *              "boxmodel_id": "10001",
     *              "brand": "",
     *              "manufacturer": "31313",
     *              "uom": "",
     *              "part_num": null,
     *              "model_num": null,
     *              "is_public": null,
     *              "product_desc": "213123",
     *              "product_image": "uploads/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *              "product_thumbnail": "uploads/thumb/kIUUBT9OvC2iN-NQoV4T_3WZgbbf10I6.jpg",
     *              "instruction": "",
     *              "create_time": "1563069062",
     *              "update_time": "1563165281",
     *              "end_date": null,
	 *              "available": 1
     *             },
     *         ]
     *     }
     * }
     * @apiSampleRequest
     */
    public function getProductInfo() {
  
          
        $productId    = I('request.productId');
        //$categoryId    = I('request.categoryId');
        //$apartmentId = I('request.organizationId');

		if(empty($productId )){
            $this->ret(2);
        }
        $wh=[
		'product_id'=>$productId,
		//'category_id'=>$categoryId,
		//'organization_id'=>$apartmentId,
		];
        $ProductInfo = D('Product')->getMember($wh);
        if(empty($ProductInfo)) { $this->ret(5);}
	    $wh=
		    [
		      'product_id'=>$productId ,
		      'product_status_code' => '1',
		    ];
	    $unitArr = D('ProductInventory')->getMember($wh);
		$ProductInfo['available']=empty($unitArr) ? 1 : 0;
		//
	    $wh1=
		    [
		      'product_id'=>$productId ,
		      'product_status_code' => '0',
		    ];
		$wh2=[
		      'product_id'=>$productId ,
		      'product_status_code' => '1',
		    ];
	    $wh3=[
		      'product_id'=>$productId ,
		      'product_status_code' => '2',
		    ];	
		$wh4=[
		      'product_id'=>$productId ,
		      'product_status_code' => '3',
		    ];
	    $wh['_complex'] = array(
              $wh1,
              $wh2,
			  $wh3,
              $wh4,
              '_logic' => 'or'
            ); 
	    $unitArr1 = D('ProductInventory')->getMember($wh1);
		$ProductInfo['delivered']=empty($unitArr1) ? 1 : 0;
        //$data  = array_values($ProductInfo);
/* 		$ProductInfo = array_values($ProductInfo);
		
		$data = [
            //'productList' => array_values($ProductInfo),
			    'product_id' => $ProductInfo['product_id'],
				'organization_id' => $ProductInfo['organization_id'],
                'product_name' => $ProductInfo['product_name'],
				'category_id' => $ProductInfo['category_id'],
				'boxmodel_id' => $ProductInfo['boxmodel_id'],
				'brand' => $ProductInfo['brand'],
				'manufacturer' => $ProductInfo['manufacturer'],
				'uom' => $ProductInfo['uom'],
				'part_num' => $ProductInfo['part_num'],
				'model_num' => $ProductInfo['model_num'],
				'is_public' => $ProductInfo['is_public'],
				'product_desc' => $ProductInfo['product_desc'],
				'product_image' => $ProductInfo['product_image'],
				'product_thumbnail' => $ProductInfo['product_thumbnail'],
				'instruction' => $ProductInfo['instruction'],
				'create_time' => $ProductInfo['create_time'],
				'update_time' => $ProductInfo['update_time'],
				'end_date' => $ProductInfo['end_date'],
				'available' => $ProductInfo['available'],
        ]; */
        $this->ret(0,$ProductInfo);
    }	
	 /**资产柜借WEB端取消预订
	 * @api {get} /asset/commitCancel 07-commitCancel
     * @apiDescription 借阅提交WEB端
     * @apiName commitCancel
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId 
     * @apiParam {String} rentalId           租借id
     *
     * @apiSuccess {Number} ret
            '0' => 'commitForRent success',                                      
            '1' => 'invalid accesstoken',                                      ,                       
            '5' => 'empty rental id',	
            '8' => 'update rental table fail',	
            '9' => 'update inventory table fail',	
            '10' => 'no rental record',				
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object} data.rent
     * @apiSuccess {String}   data.rent.rentalId
     * @apiSuccess {String}   data.rent.memberId
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "commitCancel Success",
     *     "data": {
     *         "rent": {
     *             "rentalIdId": "10007",
     *             "memberId": "10001",
     *         }
     *     }
     * }
     * @sendSampleRequest
     */
    public function commitCancel() 
	{
        $rentalId    = I('request.rentalId');
        if(empty($rentalId ))  { $this->ret(5);}
		//
	    $wh=[
		'rental_id'=>$rentalId,
		'product_status_code'=>'2',
		];
		$productinfo =D('ProductRental')->getMember($wh);
	    if(empty($productinfo)) { $this->ret(10);}
		//
        $now = time();
        $rental = array(

			'rental_status_code'=>'4',
        );

        $boxId = D('ProductRental')->updateMember($wh,$rental);
        if(empty($boxId))       { $this->ret(8);}	
		//
		
        $wh=['rfid'=>$productinfo['rfid'],];
		//更新INVENTORY状态
        $store = [
		    'member_id'=>'0',
            'update_time' => $now,
            'product_status_code' => '1',
        ];
        $storeId = D('ProductInventory')->updateMember($wh,$store);
        if(empty( $storeId))       { $this->ret(9);}

        $this->ret(0, [
            'rent' => $rental,
        ]);
    }
    //通过MEMBERid找organization （资产柜新加）   
	 /**
	 * @api {get} /asset/getBindApartment 08-getBindApartment
     * @apiDescription 通过MEMBERid找APARTMENT
     * @apiName getBindApartment
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                                     
            '1' => 'invalid accesstoken',                                                                              
            '3' => 'no matched member found',                                  
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.rfid
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "member": {
     *             "memberId": "10001",
     *             "rfid": "123"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function getBindApartment() {

		$wh['o_member_organization.member_id'] = $this->_memberId;
		$wh['o_member_organization.status'] = 1;//member must has paid
        //$wh['o_member_organization.approve_status'] = 1;//member plan must has been approved
        $courier = D('OMemberOrganization')->getMemberApartmentListN($wh);
        if($courier) {

        } else {
            $this->ret(3);
        }
        $this->ret(0,$courier);
    }
    //
    //发送邮件 （资产柜新加）   
	 /**
	 * @api {get} /asset/sendMailNew 08-sendMailNew
     * @apiDescription 发邮件
     * @apiName sendMailNew
     * @apiGroup 24-Asset
     *
     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   _toAdd
     * @apiParam {String}   _content
     * @apiParam {String}   _subject
     * @apiParam {String}   _fromAdd
     *
     * @apiSuccess {Number} ret
            '0' => 'success',                                                     
            '1' => 'invalid accesstoken',                                                                                                                
     * @apiSuccess {String} msg
     * @apiSuccess {Object} data
     * @apiSuccess {Object}   data.member
     * @apiSuccess {String}     data.member.memberId
     * @apiSuccess {String}     data.member.rfid
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "ret": 0,
     *     "msg": "Identifycard Success",
     *     "data": {
     *         "member": {
     *             "memberId": "10001",
     *             "rfid": "123"
     *         }
     *     }
     * }

     * @sendSampleRequest
     */
    public function sendMailNew() {
        $toAdd    = I('request._toAdd');
        $content    = I('request._content');
        $subject    = I('request._subject');
        $fromAdd    = I('request._fromAdd');
        if(empty($toAdd ))  { $this->ret(2);}
		if(empty($content ))  { $this->ret(3);}
		if(empty($subject ))  { $this->ret(4);}
        //$toAdd='179238846@qq.com';
        //$subject = '测试一下'; 
        //$content = '我来测试';
        //$headers[] = 'From: admin@zipcodexpress.com';
        $headers=$fromAdd;
        //$flag= mail($toAdd, $subject, $content, implode("\r\n", $headers));
        $flag= mail($toAdd, $subject, $content, $headers);
        if($flag)
        {
         $this->ret(0);
        }
        $this->ret(1);
    }
    //
}
