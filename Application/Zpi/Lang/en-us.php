<?php
return array(
    'login' => array(
        'checkemail' => array(
            '0' => 'email available',
            '1' => 'wrong param',
            '2' => 'wrong email format',
            '3' => 'email has been registered',
        ),

        'register' => array(
            '0' => 'register success',
            '1' => 'wrong param',
            '2' => 'wrong email format',
            '3' => 'email has been registered',
            '4' => 'wrong repeate password',
            '5' => 'send email fail',
            '6' => 'register fail',              
        ),

        'login' => array(
            '0' => 'login success',
            '1' => 'wrong param',
            '2' => 'wrong email format',
            '3' => 'member not exist',
            '5' => 'wrong email or password',
        ),

        'forgetpsd' => array(
            '0' => 'resend email success',
            '1' => 'wrong param',
            '2' => 'wrong email format',
            '3' => 'member not exist',
        ),

        'resetpsd' => array(
            '0' => 'resetpsd success',
            '1' => 'wrong param',
            '2' => 'member not exist',
            '3' => 'wrong repeate password',
            '4' => 'wrong verify code',                   
        ),

        'resendemail' => array(
            '0' => 'resend email success',                   
            '1' => 'need login',
        ),

        'logout' => array(
            '0' => 'resend email success',                   
            '1' => 'need login',                        
            '2' => 'member has no email',                        
        ),

        'changepsd' => array(
            '0' => 'changepsd success',
            '1' => 'need login',
            '2' => 'wrong param',
            '3' => 'wrong repeate password',
            '4' => 'member not exist',
            '5' => 'wrong old password',
        ),

        'verifyemail' => array(
            '0' => 'verify email success',              
            '1' => 'need login',              
            '2' => 'empty vcode',              
            '3' => 'fail to verify email',              
        ),

        'verifyaccount' => array(
            '0' => 'verifyAccount success',              
            '1' => 'wrong param',              
            '2' => 'wrong email format',              
            '4' => 'wrong repeate password',              
            '6' => 'register fail',              
            '8' => 'this account has been verified',
            '9' => 'this account has no apartment plan',
            '10' => 'email has been registered',
        ),
    ),
    'vcode' => array(
        'getvcode' => array(
            '0' => 'success',
            '1' => 'wrong param',
            '2' => 'fail to send vcode',
        ),
        'checkvcode' => array(
            '0' => 'check vcode success',
            '1' => 'wrong param',
            '2' => 'wrong vcode',
            '3' => 'member not found',
        ),
        'login' => array(
            '0' => 'success',                   
            '1' => 'wrong param',                   
            '2' => 'no member found',                   
        ),
    ),

    'address' => array(
        'insertaddress' => array(
            '0' => 'insert address success',
            '1' => 'need login',
            '2' => 'empty firstName',
            '3' => 'empty lastName',
            '4' => 'empty address',
            '5' => 'empty city',
            '6' => 'empty state',
            '7' => 'empty zipcode',
            '8' => 'empty longitude',
            '9' => 'empty latitude',
        ),
    ),

    'messagesms' => array(
        'getmessagelist' => array(
            '0' => 'success',               
            '2' => 'exceed max limit 100',              
        ),
        'updatemessage' => array(
            '0' => 'update success',               
            '2' => 'wrong param',                   
            '3' => 'fail to update status',             
            '4' => 'message not found',                 
            '5' => 'wrong message status',                  
        ),
    ),

    'cardcredit' => array(
        'getcardcreditlist' => array(
            '0' => 'get card list success',
            '1' => 'need login',
        ),

        'insertcardcredit' => array(
            '0' => 'insert credit card success',                                                  
            '1' => 'need login',
            '2' => 'invalid card number',
            '3' => 'empty card hoder name',                                                   
            '4' => 'empty zip code',
            '5' => 'empty expiry date or invalid expiry date (format: 0620)',            
            '6' => 'empty cvv2', 
            '7' => 'the credit card has been binded by other member', 
            '8' => 'insert credit card fail',                                                  
            '9' => 'this credit card has expired',            
        ),

        'setdefault' => array(
            '0' => 'success',            
            '1' => 'need login',            
            '2' => 'wrong param',            
            '3' => 'fail to set as default card',   
        ),

        'delete' => array(
            '0' => 'success',            
            '1' => 'need login',            
            '2' => 'wrong param',            
            '3' => 'fail to delete card',   
            '4' => 'card not exist',
        ),
    ),

    'cabinet' => array(
        'getcabinetlist' => array(
            '0' => 'get cabinet list success',
            '1' => 'need login',
        ),
        'setcabinet' => array(
            '0' => 'success set member default cabinet',              
            '1' => 'need login',              
            '2' => 'empty cabinetId',              
            '3' => 'no cabinet info found',              
            '4' => 'fail to set cabinet',              
        ),
    ),

    'photo' => array(
        'uploadphoto' => array(
            '0' => 'upload photo success',              
            '1' => 'need login',              
            '2' => 'no photos to upload',              
            '3' => 'fail to upload all photos',              
        ),
    ),

    'cargo' => array(
        'getcargoconfig' => array(
            '0' => 'get config success',                                                  
            '1' => 'need login',
        ),
    ),

    'deliver' => array(
        'getdeliverprice' => array(
            '0' => 'get deliver price success',                                                  
            '1' => 'need login',
            '2' => 'wrong param',
        ),

        'insertdeliver' => array(
            '0' => 'insert deliver success',                                                  
            '1' => 'need login',
            '2' => 'wallet not enough, please recharge and try again.',                  
            '3' => 'you need to bind a credit card to your account',                  
            '5' => 'empty box model id',                                                       
            '6' => 'empty zipcode', 
            '7' => 'empty destination zipcode', 
            '8' => 'empty receiver phone', 
            '11' => 'no member found by receiver\'s phone number', 
            '12' => 'fail to assign a box from the origin cabinet, try another cabinet or other box model', 
            '13' => 'fail to assign a box from the destination cabinet, try another cabinet or other box model', 
        ),

        'paydeliver' => array(
            '0' => 'pay success',                      
            '1' => 'need login',                      
            '2' => 'empty deliverId',                      
            '3' => 'no matched deliver order found',                      
            '4' => 'charge fail, please bind a valid credit card first and try again',                             
        ),

        'updatedeliver' => array(
            '0' => 'update deliver success',                      
            '1' => 'need login',                      
        ),

        'getdeliver' => array(
            '0' => 'get deliver success',
            '1' => 'need login',
            '2' => 'empty deliverId',
            '3' => 'empty deliver',
        ),

        'getdeliverlist' => array(
            '0' => 'get deliver list success',
            '1' => 'need login',
        ),

        'canceldeliver' => array(
            '0' => 'cancel deliver success',                                                  
            '1' => 'need login',
            '2' => 'empty deliver id',
            '3' => 'no matched order found',                                                   
            '4' => 'wrong order status',
        ),

        'getlocationlist' => array(
            '0' => 'get location list success',              
            '1' => 'need login',              
            '2' => 'empty deliverId',              
        ),
    ),

    'store' => array(
        'getstoreprice' => array(
            '0' => 'get store price success',                                                  
            '1' => 'need login',
            '2' => 'empty box model id',                                                       
            '3' => 'empty cabinet id', 
        ),

        'insertstore' => array(
            '0' => 'insert store success',                                                  
            '1' => 'need login',
            '2' => 'empty cabinet id', 
            '3' => 'empty box model id',                                                       
            '4' => 'empty hold time',                                                       
            '5' => 'empty picker\'s phone', 
            '6' => 'no member found by receiver\'s phone number', 
            '7' => 'fail to assign a box, try another cabinet or other box model', 
        ),

        'getstore' => array(
            '0' => 'get store success',
            '1' => 'need login',
            '2' => 'empty storeId',
            '3' => 'empty store',
        ),

        'getstorelist' => array(
            '0' => 'get store list success',
            '1' => 'need login',
        ),
    ),

    'pick' => array(
        'getpicklist' => array(
            '0' => 'get pick list success',
            '1' => 'need login',
            '2' => 'wrong pick list type',
        ),
        'complainpick' => array(
            '0' => 'complain success',                                                  
            '1' => 'need login',
            '2' => 'empty deliver id',
            '3' => 'no matched order found',                                                   
            '4' => 'empty content',
            '5' => 'empty photoIds',
            '6' => 'wrong order status',
        ),
    ),

    'barcode' => array(
        'show' => array(
            '1' => 'need login',
        ),
    ),

    'qrcode' => array(
        'scan' => array(
            '0' => 'scan success',
            '1' => 'need login',
            '2' => 'empty text',
            '3' => 'not support qrcode',
            '4' => 'qrcode has expired',
            '5' => 'not support bussiness type',
        ),
    ),

    'member' => array(
        'getmember' => array(
            '0' => 'success',
            '1' => 'need login',
        ),
        'switchservicemode' => array(
            '0' => 'success switch service mode',              
            '1' => 'need login',              
            '2' => 'empty or wrong mode to switch',              
            '3' => 'fail to switch service mode',              
        ),
    ),

    'profile' => array(
        'updateprofile' => array(
            '0' => 'update profile success',
            '1' => 'need login',
            '11' => 'avatar file too large',              
            '12' => 'this email has existed',              
            '13' => 'this phone has existed',              
            '21' => 'update fail',              
        ),
    ),

    'state' => array(
        'getstatelist' => array(
            '0' => 'success',                   
            '1' => 'need login',
        ),
    ),

    'statement' => array(
        'getstatementlist' => array(
            '0' => 'success',
            '1' => 'need login',
            '2' => 'need type',
            '3' => 'invalid type',
        ),
    ),

    'wallet' => array(
        'getwallet' => array(
            '0' => 'success',
            '1' => 'need login',
            '2' => 'no wallet info found',
        ),
        'recharge' => array(
            '0' => 'recharge success',
            '1' => 'need login',
            '2' => 'empty cardId or amount',
            '3' => 'fail to charge credit card',                  
            '4' => 'wrong cardId, this card is not belong to current member',                  
        ),
        'getrechargeamountconfig' => array(
            '0' => 'success',
            '1' => 'need login',
        ),
    ),

    'zippora' => array(
        'bindapartment' => array(
            '0' => 'bind apartment success',                                      
            '1' => 'need login',                                      
            '2' => 'empty apartment id',                                      
            '3' => 'empty unit id',                                      
            '5' => 'empty photo id',                                      
            '6' => 'you have binded this apartment',                             
            '7' => 'charge fail, please bind a valid credit card first and try again',                             
            '8' => 'charge fail, this apartment has not set a charge rule',                             
        ),
        'cancelbindapartment' => array(
            '0' => 'cancel binding apartment success',                                      
            '1' => 'need login',                                      
            '2' => 'empty apartment id',                                      
            '3' => 'fail to cancel, you did not bind this apartment',                                      
            '4' => 'fail to cancel, you have uncomplete order at this apartment',                                      
        ),
        'getapartmentlist' => array(
            '0' => 'success',
            '1' => 'need login',
            '2' => 'empty zipcode',
        ),
        'getbuildinglist' => array(
            '0' => 'success',
            '1' => 'need login',
            '2' => 'empty apartment id',
            '3' => 'no apartment found',                                                      
        ),
        'getroomlist' => array(
            '0' => 'success',
            '1' => 'need login',
            '2' => 'invalid apartmentId',                                           
            '3' => 'invalid buildingId',                                           
            '4' => 'no apartment found',                                                      
            '5' => 'no building found',                                                      
        ),
        'getunitlist' => array(
            '0' => 'get config success',                      
            '1' => 'need login',                                                      
            '2' => 'invalid apartmentId',                                           
            '3' => 'no apartment found',                                                      
        ),
        'getstorelist' => array(
            '0' => 'success',
            '1' => 'need login',
        ),
        'getzipporalist' => array(
            '0' => 'success',
            '1' => 'need login',
        ),
    ),

    'courier' => array(
        'insertcertificationmaterial' => array(
            '0' => 'insert certification material success',              
            '1' => 'need login',              
            '2' => 'empty photoIds',              
            '3' => 'empty descriptions',              
        ),
        'getcertificationmateriallist' => array(
            '0' => 'get certification material list success',              
            '1' => 'need login',              
        ),
        'insertstartingzip' => array(
            '0' => 'insert starting zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
        ),
        'insertdestinationzip' => array(
            '0' => 'insert destination zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
        ),
        'deletestartingzip' => array(
            '0' => 'delete starting zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
            '3' => 'no matched zipcode found',              
        ),
        'deletedestinationzip' => array(
            '0' => 'delete destination zip success',              
            '1' => 'need login',              
            '2' => 'empty zipcode',              
            '3' => 'no matched zipcode found',              
        ),
        'getziplist' => array(
            '0' => 'get zip list success',              
            '1' => 'need login',              
        ),
        'getordercountsummary' => array(
            '0' => 'get order count summary success',              
            '1' => 'need login',              
        ),
        'takeorder' => array(
            '0' => 'take order success',                   
            '1' => 'need login',                   
            '2' => 'empty deliverId',                   
            '3' => 'no matched deliver found',                   
            '4' => 'wrong deliver status',                   
            '5' => 'fail to take the order',                   
        ),
        'cancelorder' => array(
            '0' => 'cancel order success',                                                  
            '1' => 'need login',
            '2' => 'empty deliver id order order id',                  
            '3' => 'no matched order found',                                                   
            '4' => 'wrong order status',
        ),
        'getdeliverlist' => array(
             '0' => 'get order count summary success',              
             '1' => 'need login',              
             '2' => 'empty longitude or latitude',              
        ),
        'getorderlist' => array(
            '0' => 'get order list success',              
            '1' => 'need login',              
            '2' => 'wrong order type',              
        ),
        'getcabinetlist' => array(
            '0' => 'success',                   
            '1' => 'need login',                   
            '2' => 'empty longitude or latitude',                   
        ),
        'version' => array(
            '0' => 'success',                                      
        ),
        'complainfetch' => array(
            '0' => 'complain success',                                                  
            '1' => 'need login',
            '2' => 'empty deliver id',
            '3' => 'no matched order found',                                                   
            '4' => 'empty content',
            '5' => 'empty photoIds',
            '6' => 'wrong order status',
        ),
        'complainlost' => array(
            '0' => 'complain success',                                                  
            '1' => 'need login',
            '2' => 'empty deliver id',
            '3' => 'no matched order found',                                                   
            '6' => 'wrong order status',
        ),
        'insertlocation' => array(
            '0' => 'insert location success',              
            '1' => 'need login',              
            '2' => 'empty longitude',              
            '3' => 'empty latitude',              
        ),
    ),
    
    'app' => array(
        'version' => array(
            '0' => 'success',                                      
        ),
    ),

    'paypal' => array(
        'token' => array(
            '0' => 'success',                                      
            '1' => 'need login',
        ),
        'checkout' => array(
            '0' => 'success',                                      
            '1' => 'need login',
            '2' => 'fail',
        ),
    ),
    
    'store' => array(
        'addmemberstore' => array(
            '0' => 'add member store info success',                                      
            '1' => 'need login',
            '2' => 'params is not empty',
            '3' => 'add fail',
        ),
        'getmemberstorelist' => array(
            '0' => 'get member store info success',                                      
            '1' => 'need login',
            '2' => 'wrong param',
        ),
    ),
);
