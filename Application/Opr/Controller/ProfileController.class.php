<?php
namespace Opr\Controller;
use Common\Common;
use Think\Controller;

class ProfileController extends BaseController {

    /**
     * @api {post} /Profile/updateProfile updateProfile
     * @apiName updateProfile
     * @apiGroup 03-Profile
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} [nickName]
     * @apiParam {String} [firstName]
     * @apiParam {String} [lastName]
     * @apiParam {String} [householderMember]  家庭成员姓名，可以填写多个姓名，中间用英文逗号隔开， 如 Tom Jackson, Jasper Chen
     * @apiParam {String} [state]
     * @apiParam {String} [city]
     * @apiParam {String} [zipcode]
     * @apiParam {String} [addressline1]
     * @apiParam {String} [addressline2]
     * @apiParam {String} [phone]
     * @apiParam {String} [email]
     * @apiParam {String} [birth] format: 19900910 或者 1990-09-10
     * @apiParam {String} [sex] male:1 female:2
     * @apiParam {String} [avatar]
     * @apiParam {String} username
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'update profile success',              
            '1' => 'need login',              
            '11' => 'avatar file too large',                           
            '21' => 'update fail',              
     *
     * @apiSampleRequest
     */
    public function updateProfile(){

        if(empty($this->_memberId)) { $this->ret(1);}

        //only support update field one by one

        $nickName     = I('request.nickName');
        $firstName    = I('request.firstName');
        $lastName     = I('request.lastName');
        $householderMember = I('request.householderMember');
        $state        = I('request.state');
        $city         = I('request.city');
        $zipcode      = I('request.zipcode');
        $addressline1 = I('request.addressline1') ? : I('request.addressLine1');
        $addressline2 = I('request.addressline2') ? : I('request.addressLine2');
        $birth        = I('request.birth');
        $sex          = I('request.sex');
        $phone        = I('request.phone');
        $email        = I('request.email');
        $username        = I('request.username');
        $avatar       = $_FILES['avatar'];

        //allow empty field, but forbid all empty
        if (empty(array_filter(I('request.')))) $this->ret(2);

        /**
        array (size=5)
          'name' => string '544c6098a74b8e5cbee59c29928df08f.jpg' (length=36)
          'type' => string 'image/jpeg' (length=10)
          'tmp_name' => string '/tmp/php9cwMfO' (length=14)
          'error' => int 0
          'size' => int 26261
        */
        if($avatar['error'] == 0 && $avatar['size'] > 0) {
            if($avatar['size']/1024 > 10000) { $this->ret(11);}//image size < 10M
            $aname = "avatar$this->_memberId".substr(time(), 6).'.'.pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $filepath = '/tmp/'.$aname;
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $filepath);
            $avatar = "uploads/avatar/".$aname;
            $Oss = new \Common\Common\Oss();
            $Oss->uploadFile($avatar, $filepath, true);
        } else {
            $avatar = '';
        }

        D('MemberProfile')->updateProfile($this->_memberId, array_filter([
            'nick_name' => $nickName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'householder_member' => $householderMember,
            'state' => $state,
            'city' => $city,
            'zipcode' => $zipcode,
            'addressline1' => $addressline1,
            'addressline2' => $addressline2,
            'birth' => $birth,
            'sex' => $sex,
            'avatar' => $avatar,
            'username' => $username,
        ]));

        if($phone) {
            if(D('Member')->getMemberByPhone($phone)) {
               // $this->ret(13);
            }
			else 
			{
             D('Member')->updateMemberById($this->_memberId, [
                'phone' => $phone,   
				'is_profile_completed' => '1',
             ]);
			 D('MemberProfile')->updateProfile($this->_memberId, [
                'phone' => $phone,    
             ]);
			}
        }

        if($email) {
            if(D('Member')->getMemberByPhone($email)) {
                //$this->ret(12);
            }
            else 
			{
              D('Member')->updateMemberById($this->_memberId, [
                'email' => $email,
				'is_profile_completed' => '1',
              ]);
			}
        }

        //$update = ['is_profile_completed' => D('MemberProfile')->isProfileCompleted($this->_memberId) ? 1: 0];

        if(D('CardCredit')->getDefaultCard($this->_memberId)) {
            $update['status'] = 0;
        } else {
            $update['status'] = 3;
        }

        if($member['status'] == 1) {
            D('Member')->updateMemberById($this->_memberId, ['status' => 2]);
        }

        D('Member')->updateMemberById($this->_memberId, $update);
        $this->ret(0);
    }
}
