<?php

namespace App\Extensions\Gateways\BitPayIr;

use App\Helpers\ExtensionHelper;
use Illuminate\Http\Request;
use App\Classes\Extensions\Gateway;

class BitPayIr extends Gateway
{
    /**
    * Get the extension metadata
    * 
    * @return array
    */
    public function getMetadata()
    {
        return [
            'display_name' => 'BitPay',
            'version' => '1.0.0',
            'author' => 'exeller56',
            'website' => 'https://creepybarrel.ir/',
        ];
    }

    /**
     * Get all the configuration for the extension
     * 
     * @return array
     */
    public function getConfig()
    {
        return [
            [
                'name' => 'bitpay_api',
                'friendlyName' => 'BitPay Api',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name'=> 'redirect_page',
                'friendlyName' => 'Redirect Page',
                'type'=> 'text',
                'required'=> true,
            ],
        ];
    }
    
    /**
     * Get the URL to redirect to
     * 
     * @param int $total
     * @param array $products
     * @param int $invoiceId
     * @return string
     */
    public function pay($total, $products, $invoiceId)
    {
        $url = 'https://bitpay.ir/payment/gateway-send'; 
        $api = ExtensionHelper::getConfig('BitPay', 'bitpay_api');
        $amount = $total;
        $redirect = ExtensionHelper::getConfig('BitPay', 'redirect_page');
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 403);
        }
        $name = $user->name;
	    $email = $user->email;
	    $description = "Payment for Order #" . $invoiceId;
        $factorId = $invoiceId;

        $result = $this->send($url, $api, $amount, $redirect, $name, $factorId, $email, $description);

        if($result > 0 && is_numeric($result))
	    {
		        $go = "https://bitpay.ir/payment/gateway-$result-get"; 
		        header("Location: $go");
	    } else {
            return response()->json(['error' => 'Payment gateway error'], 500);
        }
    }

    public function webhook(Request $request){
        $url = 'https://bitpay.ir/payment/gateway-result-second'; 
	    $api = ExtensionHelper::getConfig('BitPay', 'bitpay_api');
        $trans_id = $request->query('trans_id');
	    $id_get = $request->query('id_get');

        if (!$trans_id || !$id_get) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

	    $result = $this->get($url,$api,$trans_id,$id_get); 
        

        $parseDecode = json_decode($result);

        if($parseDecode->status == 1){
            //true

            //mablagh ersali
            echo $parseDecode->amount;
            
            //factore ersali (ekhtiari)
            echo $parseDecode->factorId;
            
            //shomare kart pardakht konanade
            echo $parseDecode->cardNum;

        } else {
            //false

            return response()->json(['status' => 'failed'], 400);
        }
    }

    private function send($url,$api,$amount,$redirect,$factorId,$name,$email,$description){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect&factorId=$factorId&name=$name&email=$email&description=$description");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    private function get($url,$api,$trans_id,$id_get){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&id_get=$id_get&trans_id=$trans_id&json=1");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}
