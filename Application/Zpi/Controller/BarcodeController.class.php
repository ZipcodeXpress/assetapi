<?php
namespace Zpi\Controller;
use Think\Controller;

require_once(APP_PATH . 'Common/Api/barcodegen/barcodegen.php');

class BarcodeController extends BaseController {


    /**
     * @api {get} /barcode/show show
     * @apiName show
     * @apiGroup 16-Barcode

     * @apiParam {String}   text 

     *
     * @apiSampleRequest
     */
    public function show(){

        $text = I('get.text');

        $barcode = new \Common\Api\barcodegen\barcode();
        $barcode->show();
    }
}
