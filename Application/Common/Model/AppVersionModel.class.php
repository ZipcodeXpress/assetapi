<?php
namespace Common\Model;
use Think\Model;

class AppVersionModel extends Model{

    public function getLatestVersionByPlatform($platform='ios', $app='zipcodexpress') {
        $map = [
            'platform' => $platform,
            'app' => $app,
        ];
        return $this->where($map)->order('version desc')->find();
    }

    public function getAppVersionList($wh){
        return $this->where($wh)->order('create_time desc')->select();
    }

    public function insertAppVersion($data) {
        $data['create_time'] = time();
        if ($this->create($data)) {
            return $this->add();
        } else {
            return false;
        }
    }
}
