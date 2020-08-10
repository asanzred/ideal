<?php

namespace Smallworldfs\Ideal\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use Config;
use Log;
use Smallworldfs\Ideal\Libraries\Bs\IDeal\IDeal;
use Smallworldfs\Ideal\Libraries\Bs\IDeal\Exception\ResponseException;

/********************************************

THIS CONTROLLER IS ONLY FOR FLOW TESTING 
AND TUTORIAL PURPOSES.

FEEL FREE TO MOVE CODE TO YOUR OWN CONTROLLER
AND ROUTES TO MAKE YOUR SPECIFIC STUFF.

*********************************************/

class IdealController extends Controller
{
    public function getissuers(){

        // You can use this function to get available issuers or can define one directly in config file

        $ideal = new IDeal(Config::get('ideal.provider_url'));

        // The full path to the acquirer certificate. This certificate is provided by your iDeal provider and
        // must be downloaded from the merchant environment. Testing and production have different certificates.
        $ideal->setAcquirerCertificate(Config::get('ideal.acquirer_cert'), true);

        // Your merchant ID as specified in the merchant environment.
        // Testing and production each have a different merchant ID.
        $ideal->setMerchant(Config::get('ideal.merchant_id'));

        // The full path to your merchant certificate.
        $ideal->setMerchantCertificate(Config::get('ideal.merchant_cert'), true);

        // The full path to your private key.
        $ideal->setMerchantPrivateKey(Config::get('ideal.merchant_priv_key'), Config::get('ideal.merchant_priv_key_passwd'), true);

        $request  = $ideal->createDirectoryRequest();
        $response = $request->send();
        $issuers  = $response->getAllIssuers();

        return '<pre>'.print_r($issuers,true).'</pre>';

    }

    public function send(){

        $ideal = new IDeal(Config::get('ideal.provider_url'));

        // The full path to the acquirer certificate. This certificate is provided by your iDeal provider and
        // must be downloaded from the merchant environment. Testing and production have different certificates.
        $ideal->setAcquirerCertificate(Config::get('ideal.acquirer_cert'), true);

        // Your merchant ID as specified in the merchant environment.
        // Testing and production each have a different merchant ID.
        $ideal->setMerchant(Config::get('ideal.merchant_id'));

        // The full path to your merchant certificate.
        $ideal->setMerchantCertificate(Config::get('ideal.merchant_cert'), true);

        // The full path to your private key.
        $ideal->setMerchantPrivateKey(Config::get('ideal.merchant_priv_key'), Config::get('ideal.merchant_priv_key_passwd'), true);

        // Start a transaction request, amount in cents.
        $transactionRequest = $ideal->createTransactionRequest(Config::get('ideal.merchant_issuer'), 
                                                               Config::get('ideal.merchant_return_url'), 
                                                               'purchaseId', 
                                                               1999, 
                                                               'Description');

        try
        {
            $transactionResponse = $transactionRequest->send();
            echo '<pre>'.print_r($transactionRequest->getEntranceCode(),true).'</pre>';
            echo '<pre>'.print_r($transactionResponse->getTransactionId(),true).'</pre>';
            echo '<pre>'.print_r($transactionResponse->getAuthenticationUrl(),true).'</pre>';
            return "\r\nTransaction successful\r\n";
        }
        catch (ResponseException $e)
        {
            // Error handling.
            var_dump($e->getErrorMessage(), $e->getSingle('//i:suggestedAction'));
            exit();
        }
    }

    public function returntx(){

        // 'ec' and 'trxid' contain entranceCode and transactionId.
        // Validate against values stored in local database before performing a statusRequest.
        $ideal = new IDeal(Config::get('ideal.provider_url'));
        $ideal->setAcquirerCertificate(Config::get('ideal.acquirer_cert'), true);
        $ideal->setMerchant(Config::get('ideal.merchant_id'));
        $ideal->setMerchantCertificate(Config::get('ideal.merchant_cert'), true);
        $ideal->setMerchantPrivateKey(Config::get('ideal.merchant_priv_key'), Config::get('ideal.merchant_priv_key_passwd'), true);

        $transactionId = self::validateEntranceCode(Input::get('ec'), Input::get('trxid'));

        // Request the transaction status.
        $statusRequest = $ideal->createStatusRequest($transactionId);
        $statusResponse = $statusRequest->send();

        // Get the transaction status.
        if ($statusResponse->getStatus() == 'Success')
        {
            // consumerIBAN and consumerName are available on 'Success'.
            echo '<pre>'.print_r($statusResponse->getConsumerIBAN(),true).'</pre>';
            echo '<pre>'.print_r($statusResponse->getConsumerName(),true).'</pre>';
            return "\r\nTransaction complete\r\n"; 
        }
        else if ($statusResponse->getStatus() == 'Open')
        {
            return "\r\nOpen transaction\r\n";
            // On 'Open' status, try another StatusRequest later. Wait at least 5 minutes before performing
            // another StatusRequest. Use a cronjob to perform the checking in a background process.
        }
        else
        {
            return "\r\nFailure Transaction\r\n";
            // Failure, Cancelled, Expired are final.
            // The transaction has failed; explain to the user what has happened and give the option to try again.
        }
    }

    public static function validateEntranceCode($entranceCode, $transactionId)
    {
        // Validate entranceCode and transactionId here.
        
        return $transactionId;
    }
}