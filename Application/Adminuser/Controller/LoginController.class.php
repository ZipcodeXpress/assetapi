<?php
namespace Adminuser\Controller;
use Common\Common;
use Think\Controller;

class LoginController extends BaseController {
    /**
     * @api {get} /login/login 登录
     * @apiName login
     * @apiGroup 01-Login
     *
     * @apiParam {String} username      username
     * @apiParam {String} psd           md5(password)
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Number} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '11' => 'no user',
     * @apiSuccess {Object} data
     * @apiSuccess {String}   data.accessToken
     * @apiSuccess {String}   data.memberId
     *
     * @sendSampleRequest
     */
    public function login(){
        //获取用户名，密码
         $username = I('request.username');
         $psd = I('request.psd');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			      'email'    => '',
			      'apartment_ids'    => '',
			      'status'    => '',
         );
        //校验用户名密码不为空
        if (empty($username) || empty($psd)) {
            $this->ret(2, $this->retArr);
        }

        $ZPadmin = D('ZPadmin');
		if (!$ZPadmin->getMemberByPhone($username)) $this->ret(3);
		
        $member = $ZPadmin->loginPhone($username, $psd);
        if (empty($member)) {
         
           $this->ret(11,$this->retArr);
        }
        $this->ret(0, $this->loginMember($member));

    }

   /**
     * @api {post} /login/logout 02-logout
     * @apiName logout
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
               
     *
     * @sendSampleRequest
     */
    public function logout(){
        if (empty($this->_adminId)) $this->ret(1);;

        $Login = new \Common\Common\AdminLogin();
        if (empty($Login->deleteAccessToken($this->_adminId))) $this->ret(8);

        $this->ret(0);
    }
   /**
     * @api {post} /login/adminInsert 02-adminInsert
     * @apiName adminInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} username
	 * @apiParam {String} psd
	 * @apiParam {String} email
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',                
     *
     * @sendSampleRequest
     */
	public function adminInsert()
	{
		 $Username = I('request.username');
         $psd = I('request.psd');
		 $email = I('request.email');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //校验用户名密码不为空
        if (empty($Username) || empty($psd)|| empty($email)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('ZPadmin');
		if (!$ZPadmin->insertMember($email,$Username,$psd)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/adminDelById 02-adminDelById
     * @apiName adminDelById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} id
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function adminDelById()
	{
		 $id = I('request.id');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'email'    => '',
			'apartment_ids'    => '',
			'status'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('ZPadmin');
		if (!$ZPadmin->deleteMemberById($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/adminFindById 02-adminFindById
     * @apiName adminFindById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} id
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function adminFindById()
	{
		 $id = I('request.id');
         $this->retArr['data'] =array(
            'username' => '',
            'password'    => '',
			'email'    => '',
			'apartment_ids'    => '',
			'status'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('ZPadmin');
		$member=$ZPadmin->getMemberById($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
/* 		$this->retArr['data'] =array(
            'username' => $member['username'],
            'password'    =>'',// decrypt($member['password'],$member['salt']),
			'email'    => $member['email'],
			'apartment_ids'    => $member['apartment_ids'],
			'status'    => $member['status'],
        ); */
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/adminGetlist 02-adminGetlist
     * @apiName adminGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function adminGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('ZPadmin');
		$member=$ZPadmin->getList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/adminUpdate 02-adminUpdate
     * @apiName adminUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} id
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function adminUpdate()
	{
		 $id = I('request.id');
		 $info = I('request.info');
         $this->retArr['data'] =array(
            'username' => '',
            'password'    => '',
			'email'    => '',
			'apartment_ids'    => '',
			'status'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('ZPadmin');
		if (!$ZPadmin->updateMemberById($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
   /**
     * @api {post} /login/AuthassignmentInsert 03-AuthassignmentInsert
     * @apiName AuthassignmentInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} item_name
	 * @apiParam {String} user_id
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',                
     *
     * @sendSampleRequest
     */
	public function AuthassignmentInsert()
	{
		 $Username = I('request.item_name');
         $psd = I('request.user_id');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'item_name'    => '',
			'user_id'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //校验用户名密码不为空
        if (empty($Username) || empty($psd)|| empty($email)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authassignment');
		if (!$ZPadmin->insertMember($Username,$psd)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
	  /**
     * @api {post} /login/AuthassignmentInsertN 03-AuthassignmentInsertN
     * @apiName AuthassignmentInsertN
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',                
     *
     * @sendSampleRequest
     */
	public function AuthassignmentInsertN()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //校验用户名密码不为空
        if (empty($Username) || empty($psd)|| empty($email)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authassignment');
		if (!$ZPadmin->insertRecord($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/AuthassignmentDelById 03-AuthassignmentDelById
     * @apiName AuthassignmentDelById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} id
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function AuthassignmentDelById()
	{
		 $id = I('request.id');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'item_name'    => '',
			'user_id'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authassignment');
		if (!$ZPadmin->deleteMemberById($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
	   /**
     * @api {post} /login/AuthassignmentGetlist 03-AuthassignmentGetlist
     * @apiName AuthassignmentGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function AuthassignmentGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Authassignment');
		$member=$ZPadmin->getList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
   /**
     * @api {post} /login/AuthassignmentFindById 03-AuthassignmentFindById
     * @apiName AuthassignmentFindById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} id
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function AuthassignmentFindById()
	{
		 $id = I('request.id');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'item_name'    => '',
			'user_id'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authassignment');
		$member=$ZPadmin->getMemberById($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/AuthassignmentUpdate 03-AuthassignmentUpdate
     * @apiName AuthassignmentUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} id
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function AuthassignmentUpdate()
	{
		 $id = I('request.id');
		 $info = I('request.info');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'item_name'    => '',
			'user_id'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('Authassignment');
		if (!$ZPadmin->updateMemberById($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	  /**
     * @api {post} /login/AuthitemInsertN 04-AuthitemInsertN
     * @apiName AuthitemInsertN
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',                
     *
     * @sendSampleRequest
     */
	public function AuthitemInsertN()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //校验用户名密码不为空
        if (empty($Username) || empty($psd)|| empty($email)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authitem');
		if (!$ZPadmin->insertRecord($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/AuthitemDelById 04-AuthitemDelById
     * @apiName AuthitemDelById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} name
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function AuthitemDelById()
	{
		 $id = I('request.name');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'item_name'    => '',
			'user_id'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authitem');
		if (!$ZPadmin->deleteMemberById($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/AuthitemGetlist 04-AuthitemGetlist
     * @apiName AuthitemGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function AuthitemGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Authitem');
		$member=$ZPadmin->getList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
   /**
     * @api {post} /login/AuthitemFindById 04-AuthitemFindById
     * @apiName AuthitemFindById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} name
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function AuthitemFindById()
	{
		 $id = I('request.name');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authitem');
		$member=$ZPadmin->getMemberById($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/AuthitemUpdate 04-AuthitemUpdate
     * @apiName AuthitemUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} name
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function AuthitemUpdate()
	{
		 $id = I('request.name');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('Authitem');
		if (!$ZPadmin->updateMemberById($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	//
	  /**
     * @api {post} /login/AuthruleInsertN 05-AuthruleInsertN
     * @apiName AuthruleInsertN
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',                
     *
     * @sendSampleRequest
     */
	public function AuthruleInsertN()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //校验用户名密码不为空
        if (empty($Username) || empty($psd)|| empty($email)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authitem');
		if (!$ZPadmin->insertRecord($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/AuthruleDelById 05-AuthruleDelById
     * @apiName AuthruleById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} name
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function AuthruleDelById()
	{
		 $id = I('request.name');
         $this->retArr['data'] =array(
            'accessToken' => '',
            'adminId'    => '',
			'item_name'    => '',
			'user_id'    => '',
         );
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authitem');
		if (!$ZPadmin->deleteMemberById($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/AuthruleGetlist 05-AuthruleGetlist
     * @apiName AuthruleGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function AuthruleGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Authrule');
		$member=$ZPadmin->getList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
   /**
     * @api {post} /login/AuthruleFindById 05-AuthruleFindById
     * @apiName AuthruleFindById
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} name
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function AuthruleFindById()
	{
		 $id = I('request.name');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Authitem');
		$member=$ZPadmin->getMemberById($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/AuthruleUpdate 05-AuthruleUpdate
     * @apiName AuthruleUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} name
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function AuthruleUpdate()
	{
		 $id = I('request.name');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('Authitem');
		if (!$ZPadmin->updateMemberById($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	   /**
     * @api {post} /login/CabinetInsert 06-CabinetInsert
     * @apiName CabinetInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Cabinet');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetDel 06-CabinetDel
     * @apiName CabinetDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Cabinet');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetFind 06-CabinetFind
     * @apiName CabinetFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Cabinet');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetGetlist 06-CabinetGetlist
     * @apiName CabinetGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Cabinet');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetUpdate 06-CabinetUpdate
     * @apiName CabinetUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('Cabinet');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
    /**
     * @api {post} /login/CabinetAdminCardInsert 07-CabinetAdminCardInsert
     * @apiName CabinetAdminCardInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {Object} info
     * @apiSuccess {Number} ret
     * @apiSuccess {jason} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetAdminCardInsert()
	{
		$Username = I('request.info');
		//$Username = explode(",", $Username);
		 
        $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		//$Username='a=1&b=1';
		$Username =stripslashes($Username);
		$info1=$Username;
		
		//parse_str($Username,$info1);
        /* 		$arr1 = explode("&",$info1);
        foreach ((array)$arr1 as $k => $v)
	    {
        $arr2[$k] = explode("=",$v);
        }
        foreach ((array)$arr2 as $k => $v){
         $list[$v[0]] = $v[1];
        }
		$info1=$list; */
		//$info1=String_to_array($Username);
		
		//$Username1 ='{"zp_admin_id": 12345}';

		//$info1 = json_decode($json, true);
		
		//$info =addslashes($Username);
        //$info =stripslashes($info);  
		//
		//$info1 =urldecode($Username);
		//$info1 =stripslashes($Username);  
		$info1 = json_decode($info1);	
		$ZPadmin = D('CabinetAdminCard');
		$this->retArr['data'] =$info1;
	    //if (!$ZPadmin->insertMember($info)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetAdminCardDel 07-CabinetAdminCardDel
     * @apiName CabinetAdminCardDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetAdminCardDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetAdminCard');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetAdminCardFind 07-CabinetAdminCardFind
     * @apiName CabinetAdminCardFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetAdminCardFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetAdminCard');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetAdminCardGetlist 07-CabinetAdminCardGetlist
     * @apiName CabinetAdminCardGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetAdminCardGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetAdminCard');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetAdminCardUpdate 07-CabinetAdminCardUpdate
     * @apiName CabinetAdminCardUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetAdminCardUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetAdminCard');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CabinetBodyBoxInsert 08-CabinetBodyBoxInsert
     * @apiName CabinetBodyBoxInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetBodyBoxInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBodyBox');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetBodyBoxDel 08-CabinetBodyBoxDel
     * @apiName CabinetBodyBoxDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetBodyBoxDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBodyBox');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetBodyBoxFind 08-CabinetBodyBoxFind
     * @apiName CabinetBodyBoxFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBodyBoxFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBodyBox');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBodyBoxGetlist 08-CabinetBodyBoxGetlist
     * @apiName CabinetBodyBoxGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBodyBoxGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetBodyBox');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBodyBoxUpdate 08-CabinetBodyBoxUpdate
     * @apiName CabinetBodyBoxUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetBodyBoxUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetBodyBox');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CabinetBodyInsert 09-CabinetBodyInsert
     * @apiName CabinetBodyInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetBodyInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBody');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetBodyDel 09-CabinetBodyDel
     * @apiName CabinetBodyDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetBodyDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBody');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetBodyFind 09-CabinetBodyFind
     * @apiName CabinetBodyFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBodyFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBody');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBodyGetlist 09-CabinetBodyGetlist
     * @apiName CabinetBodyGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBodyGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetBody');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBodyUpdate 09-CabinetBodyUpdate
     * @apiName CabinetBodyUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetBodyUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetBodyBox');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CabinetBoxInsert 10-CabinetBoxInsert
     * @apiName CabinetBoxInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetBoxInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBox');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetBoxDel 10-CabinetBoxDel
     * @apiName CabinetBoxDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetBoxDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBox');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetBoxFind 10-CabinetBoxFind
     * @apiName CabinetBoxFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBoxFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBox');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBoxGetlist 10-CabinetBoxGetlist
     * @apiName CabinetBoxGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBoxGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetBox');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBoxUpdate 10-CabinetBoxUpdate
     * @apiName CabinetBoxUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetBoxUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetBox');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CabinetBoxModelInsert 11-CabinetBoxModelInsert
     * @apiName CabinetBoxModelInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetBoxModelInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBoxModel');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetBoxModelDel 11-CabinetBoxModelDel
     * @apiName CabinetBoxModelDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetBoxModelDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBoxModel');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetBoxModelFind 11-CabinetBoxModelFind
     * @apiName CabinetBoxModelFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBoxModelFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBoxModel');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBoxModelGetlist 11-CabinetBoxModelGetlist
     * @apiName CabinetBoxModelGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBoxModelGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetBoxModel');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBoxModelUpdate 11-CabinetBoxModelUpdate
     * @apiName CabinetBoxModelUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetBoxModelUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetBox');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CabinetErrorInsert 12-CabinetErrorInsert
     * @apiName CabinetErrorInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetErrorInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetError');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetErrorDel 12-CabinetErrorDel
     * @apiName CabinetErrorDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetErrorDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetError');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetErrorFind 12-CabinetErrorFind
     * @apiName CabinetErrorFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetErrorFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetError');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetErrorGetlist 12-CabinetErrorGetlist
     * @apiName CabinetErrorGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetErrorGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetError');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetErrorUpdate 12-CabinetErrorUpdate
     * @apiName CabinetErrorUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetErrorUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetError');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CardCreditInsert 13-CardCreditInsert
     * @apiName CardCreditInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CardCreditInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CardCredit');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CardCreditDel 13-CardCreditDel
     * @apiName CardCreditDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CardCreditDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CardCredit');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CardCreditFind 13-CardCreditFind
     * @apiName CardCreditFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CardCreditFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CardCredit');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CardCreditGetlist 13-CardCreditGetlist
     * @apiName CardCreditGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CardCreditGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CardCredit');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CardCreditUpdate 13-CardCreditUpdate
     * @apiName CardCreditUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CardCreditUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CardCredit');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/CargoTypeInsert 14-CargoTypeInsert
     * @apiName CargoTypeInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CargoTypeInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CargoType');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CargoTypeDel 14-CargoTypeDel
     * @apiName CargoTypeDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CargoTypeDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CargoType');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CargoTypeFind 14-CargoTypeFind
     * @apiName CargoTypeFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CargoTypeFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CargoType');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CargoTypeGetlist 14-CargoTypeGetlist
     * @apiName CargoTypeGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CargoTypeGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CargoType');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CargoTypeUpdate 14-CargoTypeUpdate
     * @apiName CargoTypeUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CargoTypeUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CargoType');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/ComplainInsert 16-ComplainInsert
     * @apiName ComplainInsert
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function ComplainInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Complain');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/ComplainDel 16-ComplainDel
     * @apiName ComplainDel
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function ComplainDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Complain');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/ComplainFind 16-ComplainFind
     * @apiName ComplainFind
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function ComplainFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Complain');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/ComplainGetlist 16-ComplainGetlist
     * @apiName ComplainGetlist
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function ComplainGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Complain');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/ComplainUpdate 16-ComplainUpdate
     * @apiName ComplainUpdate
     * @apiGroup 02-Logined

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function ComplainUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('Complain');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
		//
	 /**
     * @api {post} /login/CabinetBodyModelInsert 01-CabinetBodyModelInsert
     * @apiName CabinetBodyModelInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function CabinetBodyModelInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBodyModel');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/CabinetBodyModelDel 01-CabinetBodyModelDel
     * @apiName CabinetBoxModelDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function CabinetBodyModelDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBodyModel');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/CabinetBodyModelFind 01-CabinetBodyModelFind
     * @apiName CabinetBodyModelFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBodyModelFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('CabinetBodyModel');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBodyModelGetlist 01-CabinetBodyModelGetlist
     * @apiName CabinetBodyModelGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function CabinetBodyModelGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('CabinetBodyModel');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/CabinetBodyModelUpdate 01-CabinetBodyModelUpdate
     * @apiName CabinetBodyModelUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function CabinetBodyModelUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('CabinetBodyModel');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/MemberAddressInsert 02-MemberAddressInsert
     * @apiName MemberAddressInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function MemberAddressInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberAddress');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/MemberAddressDel 02-MemberAddressDel
     * @apiName MemberAddressDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function MemberAddressDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberAddress');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/MemberAddressFind 02-MemberAddressFind
     * @apiName MemberAddressFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberAddressFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberAddress');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberAddressGetlist 02-MemberAddressGetlist
     * @apiName MemberAddressGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberAddressGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('MemberAddress');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberAddressUpdate 02-MemberAddressUpdate
     * @apiName MemberAddressUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function MemberAddressUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('MemberAddress');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	//
	 /**
     * @api {post} /login/MemberCabinetInsert 03-MemberCabinetInsert
     * @apiName MemberCabinetInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function MemberCabinetInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberCabinet');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/MemberCabinetDel 03-MemberCabinetDel
     * @apiName MemberCabinetDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function MemberCabinetDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberCabinet');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/MemberCabinetFind 03-MemberCabinetFind
     * @apiName MemberCabinetFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberCabinetFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberCabinet');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberCabinetGetlist 03-MemberCabinetGetlist
     * @apiName MemberCabinetGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberCabinetGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('MemberCabinet');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberCabinetUpdate 03-MemberCabinetUpdate
     * @apiName MemberCabinetUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function MemberCabinetUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('MemberCabinet');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	 /**
     * @api {post} /login/MemberInsert 04-MemberInsert
     * @apiName MemberInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function MemberInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Member');
		if (!$ZPadmin->insertMemberN($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/MemberDel 04-MemberDel
     * @apiName MemberDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function MemberDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Member');
		if (!$ZPadmin->deleteMemberN($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/MemberFind 04-MemberFind
     * @apiName MemberFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Member');
		$member=$ZPadmin->getMemberN($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberGetlist 04-MemberGetlist
     * @apiName MemberGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Member');
		$member=$ZPadmin->getMemberListN($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberUpdate 04-MemberUpdate
     * @apiName MemberUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function MemberUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('Member');
		if (!$ZPadmin->updateMemberN($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	 /**
     * @api {post} /login/MemberProfileInsert 05-MemberProfileInsert
     * @apiName MemberProfileInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function MemberProfileInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberProfile');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/MemberProfileDel 05-MemberProfileDel
     * @apiName MemberProfileDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function MemberProfileDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberProfile');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/MemberProfileFind 05-MemberProfileFind
     * @apiName MemberProfileFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberProfileFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('MemberProfile');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberProfileGetlist 05-MemberProfileGetlist
     * @apiName MemberProfileGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function MemberProfileGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('MemberProfile');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/MemberProfileUpdate 05-MemberProfileUpdate
     * @apiName MemberProfileUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function MemberProfileUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('MemberProfile');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	 /**
     * @api {post} /login/OApartmentCabinetInsert 06-OApartmentCabinetInsert
     * @apiName OApartmentCabinetInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function OApartmentCabinetInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OApartmentCabinet');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/OApartmentCabinetDel 06-OApartmentCabinetDel
     * @apiName OApartmentCabinetDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function OApartmentCabinetDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OApartmentCabinet');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/OApartmentCabinetFind 06-OApartmentCabinetFind
     * @apiName OApartmentCabinetFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function OApartmentCabinetFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OApartmentCabinet');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/OApartmentCabinetGetlist 06-OApartmentCabinetGetlist
     * @apiName OApartmentCabinetGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function OApartmentCabinetGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('OApartmentCabinet');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/OApartmentCabinetUpdate 06-OApartmentCabinetUpdate
     * @apiName OApartmentCabinetUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function OApartmentCabinetUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('OApartmentCabinet');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	 /**
     * @api {post} /login/OApartmentInsert 07-OApartmentInsert
     * @apiName OApartmentInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function OApartmentInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OApartment');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/OApartmentDel 07-OApartmentDel
     * @apiName OApartmentDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function OApartmentDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OApartment');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/OApartmentFind 07-OApartmentFind
     * @apiName OApartmentFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function OApartmentFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OApartment');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/OApartmentGetlist 07-OApartmentGetlist
     * @apiName OApartmentGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function OApartmentGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('OApartment');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/OApartmentUpdate 07-OApartmentUpdate
     * @apiName OApartmentUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function OApartmentUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('OApartment');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
	 /**
     * @api {post} /login/OBuildingInsert 08-OBuildingInsert
     * @apiName OBuildingInsert
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function OBuildingInsert()
	{
		 $Username = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //不为空
        if (empty($Username)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OBuilding');
		if (!$ZPadmin->insertMember($Username)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}
   /**
     * @api {post} /login/OBuildingDel 08-OBuildingDel
     * @apiName OBuildingDel
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function OBuildingDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OBuilding');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/OBuildingFind 08-OBuildingFind
     * @apiName OBuildingFind
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function OBuildingFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('OBuilding');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/OBuildingGetlist 08-OBuildingGetlist
     * @apiName OBuildingGetlist
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function OBuildingGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('OBuilding');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/OBuildingUpdate 08-OBuildingUpdate
     * @apiName OBuildingUpdate
     * @apiGroup 03-Logined1

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	 * @apiParam {String} where
	 * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function OBuildingUpdate()
	{
		 $id = I('request.where');
		 $info = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $info)||empty( $id)) 
		{
            $this->ret(2, $this->retArr);
        }
		
		$ZPadmin = D('OBuilding');
		if (!$ZPadmin->updateMember($id,$info))
			$this->ret(8, $this->retArr);
		
        $this->ret(0, $this->retArr); 
	}
    private function loginMember($member) {

        $Login = new \Common\Common\AdminLogin();
        return [
            'accessToken' => $Login->setAccessToken($member['id']),
            'adminId'    => $member['id'],
						'email'    => $member['email'],
						'apartment_ids'    => $member['apartment_ids'],
						'status'    => $member['status'],
        ];
    }
    
    /**
     * @api {post} /login/DomainCabinetInsert 09-DomainCabinetInsert
     * @apiName DomainCabinetInsert
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} domain_id
	   * @apiParam {String} cabinet_id
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function DomainCabinetInsert()
	{
		 $domainid = I('request.domain_id');
		 $cabinetid = I('request.cabinet_id');
		 if (empty($this->_adminId)) $this->ret(1);
        //不为空
        if (empty($domainid)||empty($cabinetid)) {
            $this->ret(2);
          
        }
     $crtime = time();
     $domaincabinet = array(
  			  'domain_id' => $domainid,
    			'cabinet_id' => $cabinetid,
    			'create_time' => $crtime,
    );
		$Ddomain = D('DomainCabinet');
		if (!$Ddomain->insertDomainCabinet($domaincabinet)) $this->ret(8);
        $this->ret(0, $domaincabinet); 
		
	}
   /**
     * @api {post} /login/DomainCabinetDel 09-DomainCabinetDel
     * @apiName DomainCabinetDel
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function DomainCabinetDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('DomainCabinet');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/DomainCabinetFind 09-DomainCabinetFind
     * @apiName DomainCabinetFind
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function DomainCabinetFind()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty( $id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('DomainCabinet');
		$member=$ZPadmin->getMember($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/DomainCabinetGetlist 09-DomainCabinetGetlist
     * @apiName DomainCabinetGetlist
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} domain_id
	   * @apiParam {String} cabinet_id
	   * @apiParam {String} create_begin_time
	   * @apiParam {String} create_end_time
	   * @apiParam {String} page
	   * @apiParam {String} pagesize
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function DomainCabinetGetlist()
	{
		$domain_id = I('request.domain_id');
		$cabinet_id = I('request.cabinet_id');
		$create_begin_time = I('request.create_begin_time');
		$create_end_time = I('request.create_end_time');
		$page = I('request.page');
		$pagesize = I('request.pagesize');
		if (empty($this->_adminId)) $this->ret(1);
    
    $findf = array_filter([
		      'domain_cabinet.domain_id' => $domain_id,
    			'domain_cabinet.cabinet_id' => $cabinet_id,
		]);
    
    if($create_begin_time && $create_end_time) $findf['domain_cabinet.create_time'] = array(array('egt',$create_begin_time), array('elt',$create_end_time));
		$ZPadmin = D('DomainCabinet');
		$member=$ZPadmin->getDomainCabinetList($findf,$page,$pagesize);
		if (empty($member))
			$this->ret(8);
			
		$this->retArr['data'] =array();
		$this->retArr['data'] =$member;
		$this->retArr['page'] = $page;
		$this->retArr['pagesize'] = $pagesize;
		$this->retArr['total'] = D('DomainCabinet')->countDomainCabinet($findf);
    $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/DomainCabinetUpdate 09-DomainCabinetUpdate
     * @apiName DomainCabinetUpdate
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} cabinet_id
	   * @apiParam {String} domain_id
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function DomainCabinetUpdate()
	{
		 $id = I('request.cabinet_id');
		 $domain_id = I('request.domain_id');
		 
     if (empty($this->_adminId)) $this->ret(1);
        //
        if (empty($id)||empty($domain_id)) 
		{
          $this->ret(2);
        }
		
		$updatef = array_filter([
		      'domain_id' => $domain_id,
    			'cabinet_id' => $id,
		]);
		$Ddomain = D('DomainCabinet')->updateDomainCabinet($id,$updatef);
		
		
		if (!$Ddomain)	 $this->ret(8);
		
    $this->ret(0,$updatef); 
	}
	 /**
     * @api {post} /login/DomainInsert 10-DomainInsert
     * @apiName DomainInsert
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} domain_name
	   * @apiParam {String} address
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',  
         '8' => 'system error',		 
     *
     * @sendSampleRequest
     */
	public function DomainInsert()
	{
		 $domainame = I('request.domain_name');
		 $address = I('request.address');
		 if (empty($this->_adminId)) $this->ret(1);
        //不为空
        if (empty($domainame)||empty($address)) {
            $this->ret(2);
          
        }
     $domain = array(
  			  'domain_name' => $domainame,
    			'state' => I('request.state'),
    			'city' => I('request.city'),
    			'address' => $address,
    			'zipcode' => I('request.zipcode'),
    			'latitude' => I('request.latitude'),
    			'longitude' => I('request.longitude'),
    			'contract_begin' => I('request.contract_begin'),
    			'contract_end' => I('request.contract_end'),
    			'price' => I('request.price'),
    			'charge_rule' => I('request.charge_rule'),
    			'create_time' => time(),
    );
		$Ddomain = D('Domain');
		if (!$Ddomain->insertMember($domain)) $this->ret(8);
        $this->ret(0, $domain); 
		}
   /**
     * @api {post} /login/DomainDel 10-DomainDel
     * @apiName DomainDel
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',               
     *
     * @sendSampleRequest
     */
	public function DomainDel()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
        if (empty($id)) {
            $this->ret(2, $this->retArr);
        }
		$ZPadmin = D('Domain');
		if (!$ZPadmin->deleteMember($id)) $this->ret(8, $this->retArr);
        $this->ret(0, $this->retArr); 
		
	}	
   /**
     * @api {post} /login/DomainFind 10-DomainFind
     * @apiName DomainFind
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} domain_name
	   * @apiParam {String} state
	   * @apiParam {String} city
	   * @apiParam {String} zipcode
	   * @apiParam {String} create_begin_time
	   * @apiParam {String} create_end_time
	   * @apiParam {String} page
	   * @apiParam {String} pagesize
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function DomainFind()
	{
		 $domainame = I('request.domain_name');
		 $state = I('request.state');
		 $city = I('request.city');
		 $zipcode = I('request.zipcode');
		 $create_begin_time = I('request.create_begin_time');
		 $create_end_time = I('request.create_end_time');		
		 $page = I('request.page');
		 $pagesize = I('request.pagesize'); 
     $this->retArr['data'] =array();
		 if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        
//		 $find = array_filter([
//		      'domain_name' => $domainame,
//    			'state' => $state,
//    			'city' => $city,
//    			'zipcode' => $zipcode,
//		]);    
    if($domainame) $find['domain_name'] = array('LIKE', "%{$domainame}%");
    if($state) $find['state'] = array('LIKE', "%{$state}%");
    if($city) $find['city'] = array('LIKE', "%{$city}%");
    if($zipcode) $find['zipcode'] = array('LIKE', "%{$zipcode}%");
    if($create_begin_time && $create_end_time) $find['create_time'] = array(array('egt',$create_begin_time), array('elt',$create_end_time));
		
    
		$domain = D('Domain')->getDomainList($find,$page,$pagesize);
		if (empty($domain))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$domain;
		$this->retArr['page'] = $page;
		$this->retArr['pagesize'] = $pagesize;
		$this->retArr['total'] = D('Domain')->countDomain($find);
    $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/DomainGetlist 10-DomainGetlist
     * @apiName DomainGetlist
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} info
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                
     *
     * @sendSampleRequest
     */
	public function DomainGetlist()
	{
		 $id = I('request.info');
         $this->retArr['data'] =array();
		if (empty($this->_adminId)) $this->ret(1, $this->retArr);
        //
/*         if (empty( $id)) {
            $this->ret(2, $this->retArr);
        } */
		$ZPadmin = D('Domain');
		$member=$ZPadmin->getMemberList($id);
		if (empty($member))
			$this->ret(8, $this->retArr);
		$this->retArr['data'] =$member;
        $this->ret(0, $this->retArr); 
	}
	   /**
     * @api {post} /login/DomainUpdate 10-DomainUpdate
     * @apiName DomainUpdate
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} where 
	   * @apiParam {String} domain_name 
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function DomainUpdate()
	{
		 $id = I('request.where');
		 
		 $domain_name = I('request.domain_name');
     $state = I('request.state');
     $city = I('request.city');
     $address = I('request.address');
     $zipcode = I('request.zipcode');
     $latitude = I('request.latitude');
     $longitude = I('request.longitude');
     $contract_begin = I('request.contract_begin');
     $contract_end = I('request.contract_end');
     $price = I('request.price');
     $charge_rule = I('request.charge_rule');
		 
		 
		if (empty($this->_adminId)) $this->ret(1);
        //
        if (empty($id)) 
		{
          $this->ret(2);
        }
		
		$updatef = array_filter([
		      'domain_name' => $domain_name,
    			'state' => $state,
    			'city' => $city,
    			'address' => $address,
    			'zipcode' => $zipcode,
    			'latitude' => $latitude,
    			'longitude' => $longitude,
    			'contract_begin' => $contract_begin,
    			'contract_end' => $contract_end,
    			'price' => $price,
    			'charge_rule' => $charge_rule,
		]);
		$Ddomain = D('Domain')->updateDomain($id,$updatef);
		
		
		if (!$Ddomain)	 $this->ret(8);
		
    $this->ret(0,$updatef); 
	}
	

	   /**
     * @api {post} /login/DomainMemberGetlist 11-DomainMemberGetlist
     * @apiName DomainMemberGetlist
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} member_id 
	   * @apiParam {String} member_email 
	   * @apiParam {String} member_phone 
	   * @apiParam {String} domain_id 
	   * @apiParam {String} approve_begin_time 
	   * @apiParam {String} approve_end_time 
	   * @apiParam {String} approve_status 
	   * @apiParam {String} status
	   * @apiParam {String} page
	   * @apiParam {String} pagesize
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function DomainMemberGetlist()
	{
		 $member_id = I('request.member_id');
     $member_email = I('request.member_email');
     $member_phone = I('request.member_phone');
     $domain_id = I('request.domain_id');
     $approve_begin_time = I('request.approve_begin_time');
     $approve_end_time = I('request.approve_end_time');
     $approve_status = I('request.approve_status');
     $status = I('request.status');
     $page = I('request.page');
     $pagesize = I('request.pagesize');
		 
		 
		if (empty($this->_adminId)) $this->ret(1);
		
		$updatef = array_filter([
		      'domain_member.member_id' => $member_id,
    			'domain_member.domain_id' => $domain_id,
    			'domain_member.status' => $status,
    			//'domain_member.approve_status' => $approve_status,
		]);
		if($member_email) $updatef['member.email'] = array('LIKE', "%{$member_email}%");   
    if($member_phone) $updatef['member.phone'] = array('LIKE', "%{$member_phone}%");
    if(is_numeric($approve_status)) $updatef['domain_member.approve_status'] = $approve_status;
    if($approve_begin_time && $approve_end_time) $updatef['domain_member.approve_time'] = array(array('egt',$approve_begin_time), array('elt',$approve_end_time));
		$Ddomain = D('DomainMember')->getDomainMemberList($updatef,$page,$pagesize);
    if (!$Ddomain)	 $this->ret(8);
    $this->retArr['page'] = $page;
		$this->retArr['pagesize'] = $pagesize;
		$this->retArr['total'] = D('DomainMember')->countDomainMember($updatef);
    $this->retArr['data'] =$Ddomain;
    $this->ret(0, $this->retArr); 
	}
	
	
		 /**
     * @api {post} /login/StoreOrdersGetlist 12-StoreOrdersGetlist
     * @apiName StoreOrdersGetlist
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} locker_id 
	   * @apiParam {String} courier 
	   * @apiParam {String} tracking_no 
	   * @apiParam {String} pickcode 
	   * @apiParam {String} deposit_begin_time 
	   * @apiParam {String} deposit_end_time 
	   * @apiParam {String} pickup_begin_time 
	   * @apiParam {String} pickup_end_time
	   * @apiParam {String} remove_begin_time 
	   * @apiParam {String} remove_end_time
	   * @apiParam {String} page
	   * @apiParam {String} pagesize
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function StoreOrdersGetlist()
	{
		 $locker_id = I('request.locker_id');
     $courier = I('request.courier');
     $tracking_no = I('request.tracking_no');
     $pickcode = I('request.pickcode');
     $deposit_begin_time = I('request.deposit_begin_time');
     $deposit_end_time = I('request.deposit_end_time');
     $pickup_begin_time = I('request.pickup_begin_time');
     $pickup_end_time = I('request.pickup_end_time');
     $remove_begin_time = I('request.remove_begin_time');
     $remove_end_time = I('request.remove_end_time');
     $page = I('request.page');
     $pagesize = I('request.pagesize');
		 
		 
		if (empty($this->_adminId)) $this->ret(1);
		
		$updatef = array_filter([
		      'o_store.cabinet_id' => $locker_id,
    			//'o_store.courier_id' => $courier,
		]);
		if($tracking_no) $updatef['o_store.tracking_no'] = array('LIKE', "%{$tracking_no}%");   
		if($courier) $updatef['o_courier.courier_name'] = array('LIKE', "%{$courier}%"); 
    if($pickcode) $updatef['o_store.pick_code'] = array('LIKE', "{$pickcode}");
    if($deposit_begin_time && $deposit_end_time) $updatef['o_store.store_time'] = array(array('egt',$deposit_begin_time), array('elt',$deposit_end_time));
		if($pickup_begin_time && $pickup_end_time) $updatef['o_store.pick_time'] = array(array('egt',$pickup_begin_time), array('elt',$pickup_end_time));
		if($remove_begin_time && $remove_end_time) $updatef['o_store.clean_time'] = array(array('egt',$remove_begin_time), array('elt',$remove_end_time));
		$Ddomain = D('OStore')->getStoreOrdersList($updatef,$page,$pagesize);
    if (!$Ddomain)	 $this->ret(8);
    $this->retArr['page'] = $page;
		$this->retArr['pagesize'] = $pagesize;
		$this->retArr['total'] = D('OStore')->countStoreOrders($updatef);
    $this->retArr['data'] =$Ddomain;
    $this->ret(0, $this->retArr); 
	}
	
	/**
     * @api {post} /login/StoreDetail 12-StoreDetail
     * @apiName StoreDetail
     * @apiGroup 04-Domain

     * @apiParam {String} _accessToken
     * @apiParam {String} _adminId
	   * @apiParam {String} store_id 

     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
         '0' => 'success',
         '1' => 'need login',
         '2' => 'wrong param',
         '8' => 'system error',                 
     *
     * @sendSampleRequest
     */
	public function StoreDetail()
	{
		$store_id = I('request.store_id');
    if (empty($this->_adminId)) $this->ret(1);
		
		$find = array_filter([
		      'store_id' => $store_id,
		]);
		$Ddomain = D('OStore')->getStore($find);
    if (!$Ddomain)	 $this->ret(8);
    $this->retArr['data'] =$Ddomain;
    $this->ret(0, $this->retArr); 
	}
	
}
