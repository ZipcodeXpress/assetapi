<?php
namespace Common\Model;
use Think\Model;

class ZCourierOrderModel extends Model{

    public function getByDeliverIdAndCourierId($deliverId, $courierId){
        return $this->where([
            'deliver_id' => $deliverId,
            'courier_id' => $courierId,
        ])->find();
    }

    public function getCourierOrder($wh){
        return $this->where($wh)->find();
    }

    public function getCourierOrderList($wh){
        return $this->where($wh)->order('create_time desc')->select();
    }

    public function countCourierOrder($memberId, $status=null){
        $wh = [
            'courier_id' => $memberId,
        ];
        if($status) {
            $wh['status'] = $status;
        }
        return $this->where($wh)->count();
    }

    public function insertCourierOrder($data){
        $data['create_time'] = time();
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }

    public function deleteCourierOrder($data){
        return $this->where($data)->delete();
    }

    public function updateOrderStatus($orderId, $status, $extraArr=[]){

        $statusCodeConf = C('z_deliver_status_code');
        $statusCode = $statusCodeConf[$status];

        $extraArr = $extraArr ? : [];//bugfix: $extraArr must not be null before array_merge
        $data = array_merge([
            'status' => $statusCode,
        ], $extraArr);
        $ret1 = $this->data($data)->where([
            'order_id' => $orderId
        ])->save();

        return $ret1;
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
        return $this->where($wh)->order('order_id')->select();
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
