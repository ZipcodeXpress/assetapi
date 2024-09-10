<?php
namespace Common\Model;
use Think\Model;

class PhotoGroupModel extends Model{

    public function getPhotoGroup($photoGroupId){
        $photoGroup = $this->getByPhotoGroupId($photoGroupId);
        $photoArr = json_decode($photoGroup['photos'], true);
        return $photoArr;
    }

    public function insertPhotoGroup($memberId, $photoIds){
        $photoIdArr = explode(',', $photoIds);

        if(empty($memberId) || empty($photoIdArr)) {
            return false;
        }

        $photos = [];
        foreach($photoIdArr as $pId) {
            $photos[] = C('CDN_ADDRESS').$pId;
        }
        $data = [
            'member_id' => $memberId,
            'photos' => json_encode($photos),
            'create_time' => time(),
        ];

        return $this->data($data)->add();
    }
    public function insertMember($data){
       if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
    public function getBodyList($where = array()){
        return $this->where($where)->select();
    }
	public function getMember($wh){
        return $this->where($wh)->find();
    }
    public function getMemberList($wh){
        return $this->where($wh)->order('member_id')->select();
    }
	
	public function deleteMember($wh){
        return $this->where($wh)->delete();
    }
	
	public function updateMember($wh, $data){
        return $this->data($data)->where($wh)->save();
    }
	
    public function getCabinetList($where = array()){
        return $this->where($where)->select();
    }
}
