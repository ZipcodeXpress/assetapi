<?php
namespace Zpi\Controller;
use Common\Common;
use Think\Controller;

class AddressController extends BaseController {

    /**
     * @api {post} /Address/insertAddress insertAddress
     * @apiName insertAddress
     * @apiGroup 11-Address
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     * @apiParam {String} [firstName]
     * @apiParam {String} [lastName]
     * @apiParam {String} [address]
     * @apiParam {String} [city]
     * @apiParam {String} [state]
     * @apiParam {String} [zipcode]
     * @apiParam {String} [longitude]
     * @apiParam {String} [latitude]
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'insert address success',              
            '1' => 'need login',              
     *
     * @apiSampleRequest
     */
    public function insertAddress(){

        $firstName    = I('request.firstName');
        $lastName     = I('request.lastName');
        $address      = I('request.address');
        $city         = I('request.city');
        $state        = I('request.state');
        $zipcode      = I('request.zipcode');
        $longitude    = I('request.longitude');
        $latitude     = I('request.latitude');

        if(empty($firstName))  { $this->ret(2);}
        if(empty($lastName))   { $this->ret(3);}
        if(empty($address))    { $this->ret(4);}
        if(empty($city))       { $this->ret(5);}
        if(empty($state))      { $this->ret(6);}
        if(empty($zipcode))    { $this->ret(7);}
        if(empty($longitude))  { $this->ret(8);}
        if(empty($latitude))   { $this->ret(9);}

        D('MemberAddress')->insertAddress(array_filter([
            'member_id' => $this->_memberId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'state' => $state,
            'city' => $city,
            'address' => $address,
            'zipcode' => $zipcode,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ]));

        //TODO: update MemberProfile
        D('MemberProfile')->updateProfile($this->_memberId, [
            'nick_name' => $firstName.' '.$lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'state' => $state,
            'city' => $city,
            'addressline1' => $address,
            'zipcode' => $zipcode,
        ]);

        $member = D('Member')->getMemberById($this->_memberId);
        if($member['status'] == 2) {
            D('Member')->updateMemberById($this->_memberId, ['status' => 3]);
        }

        $this->ret(0);
    }
}
