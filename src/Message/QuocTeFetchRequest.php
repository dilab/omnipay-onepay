<?php

namespace Omnipay\OnePay\Message;

/**
 * QuocTe Fetch Request
 */
class QuocTeFetchRequest extends NoiDiaFetchRequest
{

    protected $liveEndpoint = 'https://onepay.vn/vpcpay/Vpcdps.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/vpcpay/Vpcdps.op';


    public function getData()
    {
        $data = parent::getData();

        return $data;
    }


    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $this->response = new FetchQuocTeResponse($this, $httpResponse->getBody());
    }

    /**
     * Encode absurd name value pair format
     */
    public function encodeData(array $data)
    {
        $output = [];
        foreach ($data as $key => $value) {
            if (strlen($value) > 0 && $key != 'Title') {
                $output[] = urlencode($key) . '=' . urlencode($value);
            }
        }

        return implode('&', $output);
    }

}
