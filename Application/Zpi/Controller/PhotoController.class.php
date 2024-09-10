<?php
namespace Zpi\Controller;
use Common\Common;
use Think\Controller;

class PhotoController extends BaseController {

    /**
     * @api {post} /Photo/uploadPhoto uploadPhoto
     * @apiName uploadPhoto
     * @apiGroup 08-Photo
     *
     * @apiParam {String} _accessToken
     * @apiParam {String} _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'upload photo success',              
            '1' => 'need login',              
            '2' => 'no photos to upload',               
            '3' => 'fail to upload all photos',              
     * @apiSuccess {Object} msg
     * @apiSuccess {Object} data
     * @apiSuccess {String[]} data.succ 上传成功的图片的ID数组
     * @apiSuccess {String[]} data.fail 上传失败的图片的ID数组
     *
     * @apiSampleRequest
     */
    public function uploadPhoto(){


        //  $_FILES

        //  "187ef4436122d1cc2f40dc2b92f0eba0": {
        //    "name": "187ef4436122d1cc2f40dc2b92f0eba0.jpg",
        //    "type": "image/jpeg",
        //    "tmp_name": "/tmp/phpL428tv",
        //    "error": 0,
        //    "size": 34705
        //  },
        //  "187ef4436122d1cc2f40dc2b92f0eba0": {
        //    "name": "187ef4436122d1cc2f40dc2b92f0eba0.jpg",
        //    "type": "image/jpeg",
        //    "tmp_name": "/tmp/phpL428tv",
        //    "error": 0,
        //    "size": 31725
        //  }

        if(empty($_FILES)) {
            $this->ret(2);
        }

        $succArr = [];
        $failArr = [];

        foreach($_FILES as $key => $photo) {
            //$key not used
            $name = $photo['name'];

            if($photo['error'] == 0 && $photo['size'] > 0) {

                //limit image size < 10M
                if($photo['size']/1024 > 10000) {
                    $failArr[] = $name;
                    continue;
                }

                $filepath = '/tmp/'.$name;
                move_uploaded_file($_FILES[$key]["tmp_name"], $filepath);

                $Oss = new \Common\Common\Oss();
                $Oss->uploadFile($key, $filepath, true);

                $succArr[] = $key;
            } else {
                $failArr[] = $key;
            }
        }

        $this->ret($failArr ? 3 : 0, [
            'succ' => $succArr,
            'fail' => $failArr,
        ]);
    }
}
