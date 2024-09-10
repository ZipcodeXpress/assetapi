<?php
namespace Common\Model;
use Think\Model;
use Think\Log\Driver;

class ZPadminModel extends Model{
    const MEMBER_RESET_PSD_EXPIRE = 600;

    protected $trueTableName = 'zp_admin';

    protected $_auto = array(
    );

    private static $_log = null;

    private function getLogger(){
        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('adminlogin');
        }
        return self::$_log;
    }

    private function getResetPsdKey($username, $vid){
        return sprintf('%d_%s', $username, $vid);
    }
	
    public function getList($wh){
        return $this->where($wh)->order('id')->select();
    }
    /* 插入用户信息
     * @params    username    用户账号
     * @params    psd      用户密码
     * @return
    */
    public function insertMember($email, $username, $psd){
        $salt = mt_rand(1,10000);
        $now = time();
        $status = 10;
		$data = array(
            'email'=>$email,
            'username'=>$username,
            'password'=>md5($psd . $salt),
            'salt'=>$salt,
            'created_at'=>$now,
            'status'=>$status
        );
        if ($this->create($data, self::MODEL_INSERT)) {
            return $this->add();
        } else{
            return FALSE;
        }
    }

    /* 获取用户信息
     * @params    username    用户账号
     * @params    field    信息字段，默认取全部
     * @return
    */
    public function getMemberByPhone($username, $field = '*'){
        return $this->field($field)->where(array('username'=>$username))->find();
    }

    /* 根据memberid获取用户信息
     * @params    username    用户账号
     * @params    field    信息字段，默认取全部
     * @return
    */
    public function getMemberById($adminId, $field='*'){
        return $this->field($field)->where(array('id'=>$adminId))->find();
    }

    /* 根据email获取用户信息
     * @params    email    用户email
     * @params    field    信息字段，默认取全部
     * @return
    */
    public function getMemberByEmail($email, $field='*'){
        return $this->field($field)->where(array('email'=>$email))->find();
    }

    public function getActiveMemberByEmail($email, $field='*'){
        return $this->field($field)->where(array('email'=>$email, 'status'=>0))->find();
    }

    public function getActiveMemberByUsername($username, $field='*'){
        return $this->field($field)->where(array('username'=>$username, 'status'=>0))->find();
    }

    /* 更新用户信息
     * @params    username    用户账号
     * @params    data     更新数据
     * @return
    */
    public function updateMember($username, $data){
        if ($this->create($data,self::MODEL_UPDATE)) {
            return $this->where(array('username'=>$username))->save();
        } else{
            return FALSE;
        }
    }

    /* 更新id更新用户信息
     * @params    phone    用户账号
     * @params    data     更新数据
     * @return
    */
    public function updateMemberById($adminId, $data){
        return $this->where(array('id'=>$adminId))->data($data)->save();
    }

    public  Function isEmail($Argv){
        $RegExp='/^[a-z0-9][a-z\.0-9-_]+@[a-z0-9_-]+(?:\.[a-z]{0,3}\.[a-z]{0,2}|\.[a-z]{0,3}|\.[a-z]{0,2})$/i';
        return preg_match($RegExp,$Argv) ? $Argv : false;
    }

    /* 用户登录验证（支持邮箱或者用户）
     * @params    email_phone    用户账号
     * @params    password       密码
     * @return
    */
    public function login($email_username,$password,$remember=false){
        $loginfo = array(
            'result' => 'member_login_fail',
            'email_phone' => $email_username,
        );
        $email = $this->isEmail($email_username);
        if($email){
            $data = $this->getMemberByEmail($email_username);
        }
        else{
            $data = $this->getMemberByPhone($email_username);
        }
        if (empty($data)) {
            $loginfo['isEmail'] = $email;
            $loginfo['reason'] = 'no_member';
            $this->getLogger()->write($loginfo);
            return false;
        }
	 
        if(md5($password.$data['salt'])==$data['password']){
            //登录成功 写入登录信息
            if($remember==true){
                session(array('name'=>'session_id','expire'=>2592000));
            }
            session('admin_id',$data['id']);


			$this->updateMemberById($data['id'], array('lastlogin_time'=>time()));

            $loginfo['result'] = 'member_login_succ';
            $loginfo['adminId'] = $data['admin_id'];
            $this->getLogger()->write($loginfo);
            return $data;
        }else{
            $loginfo['salt'] = $data['salt'];
            $loginfo['password_hash'] = $password;
            $loginfo['curPsd'] = $data['password'];
            $loginfo['reason'] = 'psd_error';
            $this->getLogger()->write($loginfo);
            return false;
        }
		
    }

    public function checkPsd($adminId, $psd){
        return $this->where([
            'id' => $adminId,
            'password' => $psd,
        ])->find();
    }

    public function checkOldPwd($adminId, $oldPwd){
        $data = $this->getMemberById($adminId);
        if (empty($data)) {
            return false;
        }
        if(md5($oldPwd.$data['salt'])==$data['password']){
            return true;
        }else{
            return false;
        }
    }
   /* 用户注册
     * @params    email         用户email
     * @params    phone         用户手机号
     * @params    psd           密码
     * @return
    */
    public function register($email = '', $username='', $psd = ''){

        $id = $this->insertMember($email, $username, $psd);

        if(empty($id)) return false;

        return $id;
    }
  
    /* 用户通过Email登录
     * @params    email         用户账号
     * @params    password      密码
     * @return
    */
    public function loginEmail($email, $password=false, $createMember=false){

        $member = $this->getMemberByEmail($email);
        if($member) {
            //if member exists, check psd if psd not false
            if($password === false || ($password !== false && md5($password.$member['salt']) == $member['password'])) {
                //if without password(login with vcode)
                if($remember==true){ session(array('name'=>'session_id','expire'=>2592000)); }
                session('id', $member['admin_id']);

                $this->updateMemberById($member['admin_id'], array('lastlogin_time'=>time()));
                return $member;
            }else{
                return false;
            }
        }else{

            if($createMember) {
                $psd1 = mt_rand(100000, 999999);
                $id = $this->register($email, '', md5($psd1));
                if($id) {
                    return $this->getMemberById($id);
                } else {
                    return false;
                }
            } else {
                return false;
            } 
	
        }
    }

    public function loginPhone($username, $password = false,  $createMember=false){

        $member = $this->getMemberByPhone($username);
        if($member) {
            //if member exists, check psd if psd not false
            if($password === false || ($password !== false && md5($password.$member['salt']) == $member['password'])) {
                //if without password(login with vcode)
                if($remember==true){ session(array('name'=>'session_id','expire'=>2592000)); }
                //session('adminId', $member['id']);
				//$this->_adminId =$member['id'];
				session('id', $member['id']);
				$this->updateMemberById($member['id'], array('lastlogin_time'=>time()));
                return $member;
            }else{
                return false;
            }
        }else
		{

            if($createMember) {
                $psd1 = mt_rand(100000, 999999);
                $id = $this->register('', $phone, md5($psd1));
                if($id) {
                    return $this->getMemberById($id);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function deleteMemberById($adminId){
        return $this->where('id = "%s"', $adminId)->delete();
    }
}
