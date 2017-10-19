<?php
include_once('Paypal.php');
if(!class_exists("Appointzilla")) {
    class Scientechpaypal {
        // Create an instance of the paypal library
        function TakePayment($ApPaymentEmail, $CurrencyCode, $SuccessCurrentUrl, $FailedCurrentUrl, $ServiceName, $ServiceCost, $LastAppointmentId, $DiscountRate) {
            //include_once('Paypal.php');
            $myPaypal = new Paypal();
            // Specify your paypal email
            $myPaypal->addField('business', $ApPaymentEmail);
            // Specify the currency
            $myPaypal->addField('currency_code', $CurrencyCode);                    //$currencyname
            // Specify the url where paypal will send the user on success/failure
            $myPaypal->addField('return', $SuccessCurrentUrl);                      //Success
            $myPaypal->addField('cancel_return', $FailedCurrentUrl);                //Failed
            // Specify the url where paypal will send the IPN
            $myPaypal->addField('notify_url', $SuccessCurrentUrl);
            // Specify the product information
            $myPaypal->addField('item_name', $ServiceName);
            $myPaypal->addField('amount', $ServiceCost);                            //$ServiceCost
            $myPaypal->addField('item_number', $LastAppointmentId);
            $myPaypal->addField('custom', "");                                      //used for coupon code
            $myPaypal->addField('discount_rate', $DiscountRate);                    //Discount rate (percentage) associated with an item.
            //$myPaypal->enableTestMode();                                          // Uncomment Enable test mode if needed
            // Let's start the train!
            $myPaypal->submitPayment();
        }
    }
}