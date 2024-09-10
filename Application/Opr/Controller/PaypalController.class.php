<?php
namespace Opr\Controller;
use Think\Controller;
use Think\Log\Driver;

class PaypalController extends BaseController {
	
    public static $_log = null;
    public static $_gw = null;

    public function __construct() {

        parent::__construct();

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('paypal');
        }
        #Cannot mix OAuth credentials (clientId, clientSecret, accessToken) with key credentials (publicKey, privateKey, environment, merchantId)
        self::$_gw = new \Braintree_Gateway(array(
            #'accessToken'  => C('paypal.access_token'),
            #'clientId'     => C('paypal.client_id'),
            #'clientSecret' => C('paypal.client_secret'),
            'environment'  => C('braintree.environment'),
            'merchantId'   => C('braintree.merchant_id'),
            'publicKey'    => C('braintree.public_key'),
            'privateKey'   => C('braintree.private_key'),
        ));

        if(empty($this->_memberId)) { $this->ret(1);}
    }

    /**
     * @api {get} /paypal/token token
     * @apiName token
     * @apiGroup 05-Paypal

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {Object} data
     * @apiSuccess {String} data.token
     * @apiSuccess {String} msg
            '0' => 'success',                      
            '1' => 'need login',                      
     *
     * @apiSampleRequest
     */
    public function token() {
        $clientToken = self::$_gw->clientToken()->generate();
        $this->ret(0, [
            'token' => $clientToken,
        ]);
    }

    public function index() {
        $clientToken = self::$_gw->clientToken()->generate();
        $this->assign('orderNo', 'UNIBOX'.date('Ymd').rand(999,9999));
        $this->assign('token', $clientToken);
        $this->display();
    }

    /**
     * @api {get} /paypal/checkout checkout
     * @apiName checkout
     * @apiGroup 05-Paypal

     * @apiParam {String}   _accessToken
     * @apiParam {String}   _memberId
     * @apiParam {String}   payment_method_nonce
     * @apiParam {String}   amount
     *
     * @apiSuccess {Number} ret
     * @apiSuccess {String} msg
            '0' => 'success',                      
            '1' => 'need login',                      
            '2' => 'fail',                      
     *
     * @apiSampleRequest
     */
    public function checkout() {
        $result = self::$_gw->transaction()->sale([
            "amount" => $_POST['amount'],
            "paymentMethodNonce" => $_POST['payment_method_nonce'],
            'options' => [
                'submitForSettlement' => True
            ]
        ]);
        if ($result->success) {
            //TODO: update wallet
            //print_r("Success ID: " . $result->transaction->id);
            $stArr = [
                'amount' => -$_POST['amount'],
                'statement_type' => 'recharge',
                'statement_desc' => 'recharge',
                'channel' => 'paypal',
            ];
            $Statement = D('Statement');
            $Statement->insertStatement($this->_memberId, $stArr);

            $Wallet = D('Wallet');
            $stArr['channel'] = 'account';
            $Wallet->addWallet($this->_memberId, $_POST['amount'], 0, 0, $stArr);

            if(C('recharge_plus')) {
                $rechargeAmountConfig = C('recharge_amount_config');
                $plusAmount = $rechargeAmountConfig[$_POST['amount']];
                if($plusAmount > 0) {
                    $stArr['statement_desc'] = 'plus';
                    #$Wallet->addWallet($this->_memberId, $plusAmount, 0, 0, $stArr);
                    $Wallet->addWallet($this->_memberId, 0, 0, $plusAmount * C('ubi_rate'), $stArr);
                }
            }

            $this->ret(0);
        } else {
            //print_r("Error Message: " . $result->message);
            $this->ret(2, NULL, $result->message);
        }
    }
}
