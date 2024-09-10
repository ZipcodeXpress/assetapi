<?php
namespace Adminuser\Model;
use Think\Model;
use Think\Log\Driver;

class MemberModel extends Model{
    const MEMBER_RESET_PSD_EXPIRE = 600;

    protected $trueTableName = 'member';

    protected $_auto = array(
    );

    private static $_log = null;

    private function getLogger(){
        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('login');
        }
        return self::$_log;
    }

    private function getResetPsdKey($phone, $vid){
        return sprintf('%d_%s', $phone, $vid);
    }

    /* 插入用户信息
     * @params    phone    用户账号
     * @params    psd      用户密码
     * @return
    */
    public function insertMember($email, $phone, $psd){
        $salt = mt_rand(1,10000);
        $now = time();
        $status = 1;//by default 1
        $data = array(
            'email'=>$email,
            'phone'=>$phone,
            'password'=>md5($psd . $salt),
            'salt'=>$salt,
            'register_time'=>$now,
            'register_type'=>'register',
            'status'=>$status
        );
        if ($this->create($data, self::MODEL_INSERT)) {
            return $this->add();
        } else{
            return FALSE;
        }
    }

    /* 获取用户信息
     * @params    phone    用户账号
     * @params    field    信息字段，默认取全部
     * @return
    */
    public function getMemberByPhone($phone, $field = '*'){
        return $this->field($field)->where(array('phone'=>$phone))->find();
    }

    /* 根据memberid获取用户信息
     * @params    phone    用户账号
     * @params    field    信息字段，默认取全部
     * @return
    */
    public function getMemberById($memberId, $field='*'){
        return $this->field($field)->where(array('member_id'=>$memberId))->find();
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

    public function getActiveMemberByPhone($phone, $field='*'){
        return $this->field($field)->where(array('phone'=>$phone, 'status'=>0))->find();
    }

    /* 更新用户信息
     * @params    phone    用户账号
     * @params    data     更新数据
     * @return
    */
    public function updateMember($phone, $data){
        if ($this->create($data,self::MODEL_UPDATE)) {
            return $this->where(array('phone'=>$phone))->save();
        } else{
            return FALSE;
        }
    }

    /* 更加memberid更新用户信息
     * @params    phone    用户账号
     * @params    data     更新数据
     * @return
    */
    public function updateMemberById($memberId, $data){
        return $this->where(array('member_id'=>$memberId))->data($data)->save();
    }

    public  Function isEmail($Argv){
        $RegExp='/^[a-z0-9][a-z\.0-9-_]+@[a-z0-9_-]+(?:\.[a-z]{0,3}\.[a-z]{0,2}|\.[a-z]{0,3}|\.[a-z]{0,2})$/i';
        return preg_match($RegExp,$Argv) ? $Argv : false;
    }

    /* 用户登录验证（支持邮箱或者手机号）
     * @params    email_phone    用户账号
     * @params    password       密码
     * @return
    */
    public function login($email_phone,$password,$remember=false){
        $loginfo = array(
            'result' => 'member_login_fail',
            'email_phone' => $email_phone,
        );
        $email = $this->isEmail($email_phone);
        if($email){
            $data = $this->getMemberByEmail($email_phone);
        }
        else{
            $data = $this->getMember($email_phone);
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
            session('member_id',$data['member_id']);

            $this->updateMemberById($data['member_id'], array('lastlogin_time'=>time()));

            $loginfo['result'] = 'member_login_succ';
            $loginfo['memberId'] = $data['member_id'];
            $this->getLogger()->write($loginfo);
            return $data;
        }else{
            $loginfo['salt'] = $data['salt'];
            $loginfo['psd'] = $password;
            $loginfo['curPsd'] = $data['password'];
            $loginfo['reason'] = 'psd_error';
            $this->getLogger()->write($loginfo);
            return false;
        }
    }

    public function checkPsd($memberId, $psd){
        return $this->where([
            'member_id' => $memberId,
            'password' => $psd,
        ])->find();
    }

    public function checkOldPwd($memberId, $oldPwd){
        $data = $this->getMemberById($memberId);
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
    public function register($email = '', $phone='', $psd = ''){

        $id = $this->insertMember($email, $phone, $psd);

        if(empty($id)) return false;

        $ret2 = D('Wallet')->insertWallet($id);

        $ret3 = D('MemberProfile')->insertProfile($id, [
            'phone' => $phone,
            'email' => $email,
        ]);

        return $id;
    }
    /*共享柜用户注册流程，自动增加userid*/
    public function registerDomain($email = '', $phone='', $psd = ''){

        $id = $this->insertMember($email, $phone, $psd);

        if(empty($id)) return false;

        $ret2 = D('Wallet')->insertWallet($id);

        $ret3 = D('MemberProfile')->insertProfile($id, [
            'phone' => $phone,
            'email' => $email,
            'username' => mt_rand(1000,100000),
        ]);

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
                session('member_id', $member['member_id']);

                $this->updateMemberById($member['member_id'], array('lastlogin_time'=>time()));
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

    public function loginPhone($phone, $password = false,  $createMember=false){

        $member = $this->getMemberByPhone($phone);
        if($member) {
            //if member exists, check psd if psd not false
            if($password === false || ($password !== false && md5($password.$member['salt']) == $member['password'])) {
                //if without password(login with vcode)
                if($remember==true){ session(array('name'=>'session_id','expire'=>2592000)); }
                session('member_id', $member['member_id']);

                $this->updateMemberById($member['member_id'], array('lastlogin_time'=>time()));
                return $member;
            }else{
                return false;
            }
        }else{

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
    
        public function loginId($id, $password = false,  $createMember=false){

        $member = $this->getMemberById($id);
        if($member) {
            //if member exists, check psd if psd not false
            if($password === false || ($password !== false && md5($password.$member['salt']) == $member['password'])) {
                //if without password(login with vcode)
                if($remember==true){ session(array('name'=>'session_id','expire'=>2592000)); }
                session('member_id', $member['member_id']);

                $this->updateMemberById($member['member_id'], array('lastlogin_time'=>time()));
                return $member;
            }else{
                return false;
            }
        }else{
           return false;
            
        }
    }

    public function deleteMemberById($memberId){
        return $this->where('member_id = "%s"', $memberId)->delete();
    }
    public function insertMemberN($data){
       if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function getBodyListN($where = array()){
        return $this->where($where)->select();
    }
		public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberListN($wh){
        return $this->where($wh)->order('member_id')->select();
    }
	
	public function deleteMemberN($wh){
        return $this->where($wh)->delete();
    }
	
	public function updateMemberN($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
	
    public function getCabinetListN($where = array()){
        return $this->where($where)->select();
    }
}
