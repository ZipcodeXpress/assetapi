<?php
namespace Common\Common;
use Think\Log\Driver;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

define("AUTHORIZENET_LOG_FILE", "phplog");

class PayAuthorize {

    static $_log = null;
    private $ma = null;

    public function __construct() {

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('authorize.net');
        }

        $this->ma = new AnetAPI\MerchantAuthenticationType();
        $this->ma->setName(C('authorize.authentication_name'));
        $this->ma->setTransactionKey(C('authorize.authentication_key'));
        $this->apiurl = C('authorize.api');
    }

    public function create_profile($member, $profile=null, $payment) {
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create a Customer Profile Request
        //  1. (Optionally) create a Payment Profile
        //  2. (Optionally) create a Shipping Profile
        //  3. Create a Customer Profile (or specify an existing profile)
        //  4. Submit a CreateCustomerProfile Request
        //  5. Validate Profile ID returned

        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($payment['card_number']);
        $creditCard->setExpirationDate($payment['exp_date']);
        $creditCard->setCardCode($payment['cvv2']);
        // Add the payment data to a paymentType object
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentProfile->setDefaultpaymentProfile(true);

        if($profile) {
            // Set the customer's Bill To address
            $billTo = new AnetAPI\CustomerAddressType();
            $billTo->setFirstName($profile['first_name']);
            $billTo->setLastName($profile['last_name']);
            //$billTo->setCompany($profile['company']);
            $billTo->setAddress($profile['address']);
            $billTo->setCity($profile['city']);
            $billTo->setState($profile['state']);
            $billTo->setZip($profile['zipcode']);
            //$billTo->setCountry('USA');
            //$billTo->setPhoneNumber("888-888-8888");
            //$billTo->setfaxNumber("999-999-9999");
            $paymentProfile->setBillTo($billTo);
        }

        $paymentProfiles[] = $paymentProfile;

        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription("unibox customer profile");
        $customerProfile->setMerchantCustomerId($member['member_id']);
        $customerProfile->setEmail($member['email']);
        $customerProfile->setpaymentProfiles($paymentProfiles);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($this->ma);
        $request->setRefId($refId);
        $request->setProfile($customerProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($this->apiurl);
  
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $paymentProfiles = $response->getCustomerPaymentProfileIdList();
            $ret = [
                'status' => 0,
                'profileId' => $response->getCustomerProfileId(),
                'paymentProfileId' => $paymentProfiles[0],
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            $ret = [
                'status' => 1,
                'err' => $errorMessages[0]->getCode(),
                'msg' => $errorMessages[0]->getText(),
            ];
        }
        return $ret;
    }

    public function create_payment_profile($profileId, $profile=null, $payment) {
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($payment['card_number']);
        $creditCard->setExpirationDate($payment['exp_date']);
        $creditCard->setCardCode($payment['cvv2']);
        // Add the payment data to a paymentType object
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentProfile->setDefaultpaymentProfile(true);

        if($profile) {
            // Set the customer's Bill To address
            $billTo = new AnetAPI\CustomerAddressType();
            $billTo->setFirstName($profile['first_name']);
            $billTo->setLastName($profile['last_name']);
            //$billTo->setCompany($profile['company']);
            $billTo->setAddress($profile['address']);
            $billTo->setCity($profile['city']);
            $billTo->setState($profile['state']);
            $billTo->setZip($profile['zipcode']);
            //$billTo->setCountry('USA');
            //$billTo->setPhoneNumber("888-888-8888");
            //$billTo->setfaxNumber("999-999-9999");
            $paymentProfile->setBillTo($billTo);
        }

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($this->ma);
        $request->setRefId($refId);

        // Add an existing profile id to the request
        $request->setCustomerProfileId($profileId);
        $request->setPaymentProfile($paymentProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($this->apiurl);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            $ret = [
                'status' => 0,
                'paymentProfileId' => $response->getCustomerPaymentProfileId(),
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            $ret = [
                'status' => 1,
                'err' => $errorMessages[0]->getCode(),
                'msg' => $errorMessages[0]->getText(),
            ];

        }
        return $ret;
    }

    public function get_payment_profile($profileId, $paymentProfileId) {
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        //request requires customerProfileId and customerPaymentProfileId
        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId($profileId);
        $request->setCustomerPaymentProfileId($paymentProfileId);

        // Create the controller and get the response
        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($this->apiurl);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            $ret = [
                'status' => 0,
                'paymentProfileId' => $response->getCustomerPaymentProfileId(),
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            $ret = [
                'status' => 1,
                'err' => $errorMessages[0]->getCode(),
                'msg' => $errorMessages[0]->getText(),
            ];

        }
        return $ret;
    }

    public function charge_payment_profile($profileId, $paymentProfileId, $amount) {
        
        // Set the transaction's refId
        $refId = $invoiceNum = 'UB'.date('ymd').substr(time(), 4).rand(100000, 999999);

        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($profileId);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentProfileId);
        $profileToCharge->setPaymentProfile($paymentProfile);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($invoiceNum);
        $order->setDescription("Unibox Charge");

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction"); 
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->ma);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($this->apiurl);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            $tres = $response->getTransactionResponse();
            if($tres != null && $tres->getMessages() != null) {
                $tresMsgCode = $tres->getMessages()[0]->getCode();
                $tresMsgDesc = $tres->getMessages()[0]->getDescription();
                $ret = [
                    'status' => $tresMsgCode == 1 ? 0 : 1,
                    'msg' => $tresMsgDesc,
                    'data' => [
                        'authCode' => $tres->getAuthCode(),
                        'transId' => $tres->getTransId(),
                        'transHash' => $tres->getTransHash(),
                        'accountNumber' => $tres->getAccountNumber(),
                        'accountType' => $tres->getAccountType(),
                        'refId' => $refId,
                        'amount' => $amount,
                        'ctr' => $tresMsgDesc,
                    ],
                ];
            } else {
                if($tres->getErrors() != null) {
                    $ret = [
                        'status' => 1,
                        'err' => $tres->getErrors()[0]->getErrorCode(),
                        'msg' => $tres->getErrors()[0]->getErrorText(),
                        'data' => [
                            'refId' => $refId,
                        ],
                    ];
                }
            }
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            $ret = [
                'status' => 1,
                'err' => $errorMessages[0]->getCode(),
                'msg' => $errorMessages[0]->getText(),
                'data' => [
                    'refId' => $refId,
                ],
            ];

        }
        return $ret;
    }
}
