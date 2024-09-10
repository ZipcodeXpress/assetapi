<?php
namespace Opr\Model;
use Think\Model;


class KioskAdminModel extends Model{
    protected $trueTableName = 'kiosk';

    public function getKioskIdList($wh=[]){
        return $this->field('kiosk_id')->where($wh)->select();
    }

    public function getKioskList($wh=[]){
        return $this->where($wh)->select();
    }

    public function getKioskInfo($kioskId){
        return $this->where(array('kiosk_id'=>['in', $kioskId]))->find();
    }

    public function addKioskInfo($data){
        if($data['kiosk_image']) { $data['kiosk_image'] = str_replace(C('CDN_ADDRESS'), '', $data['kiosk_image']);}
        return $this->data($data)->add();
    }

    public function updateKioskInfo($id, $data){
        if($data['kiosk_image']) { $data['kiosk_image'] = str_replace(C('CDN_ADDRESS'), '', $data['kiosk_image']);}
        return $this->where(array('kiosk_id'=>$id))->data($data)->save();
    }

    public function deleteKiosk($id){
        return $this->where(array('kiosk_id'=>$id))->delete();
    }
}
