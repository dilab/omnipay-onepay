<?php

namespace Omnipay\OnePay\Message;

/**
 * Noi Dia Complete Purchase Request
 */
class NoiDiaCompletePurchaseRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://onepay.vn/onecomm-pay/Vpcdps.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/onecomm-pay/Vpcdps.op';

    public function getData()
    {
        $data = $this->httpRequest->request->all();
        $data['computed_hash_value'] = $this->computeHash($data);
        return $data;
    }

    public function sendData($data)
    {
        return new NoiDiaCompletePurchaseResponse($this, $data);
    }

    /**
     * Encode absurd name value pair format
     */
    public function encodeData(array $data)
    {
        $output = [];

        foreach ($data as $key => $value) {
            $output[] = $key . '[' . strlen($value) . ']=' . $value;
        }

        return implode('&', $output);
    }

    protected function computeHash($data)
    {
        unset ($data['vpc_SecureHash']);

        // set a flag to indicate if hash has been validated

        $secureHash = $this->getSecureHash();

        if (strlen($secureHash) > 0 && $data['vpc_TxnResponseCode'] != "7") {

            ksort($data);

            $stringHashData = "";

            // sort all the incoming vpc response fields and leave out any with no value
            foreach ($data as $key => $value) {
                if ($key != "vpc_SecureHash" &&
                    (strlen($value) > 0) &&
                    ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))
                ) {
                    $stringHashData .= $key . "=" . $value . "&";
                }
            }

            $stringHashData = rtrim($stringHashData, "&");
            
            return hash_hmac('SHA256', $stringHashData, pack('H*', $secureHash));
        }

        return null;
    }

}
