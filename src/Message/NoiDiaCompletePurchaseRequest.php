<?php

namespace Omnipay\OnePay\Message;

use Guzzle\Http\Message\RequestInterface;

/**
 * Noi Dia Complete Purchase Request
 */
class NoiDiaCompletePurchaseRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://onepay.vn/onecomm-pay/Vpcdps.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/onecomm-pay/Vpcdps.op';


    public function getData()
    {
        $data = $this->getBaseData();
        $data['vpc_MerchTxnRef'] = $this->getVpc_MerchTxnRef();

        return $data;
    }


    public function getConfirmReference()
    {
        $dataConfirm = [];

        if ($this->checkHash()) {
            $dataConfirm['responsecode'] = 1;
            $dataConfirm['desc'] = 'confirm-success';
        } else {
            $dataConfirm['responsecode'] = 0;
            $dataConfirm['desc'] = 'confirm-fail';
        }

        return $dataConfirm;
    }


    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $this->response = new FetchResponse($this, $httpResponse->getBody());
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


    protected function checkHash()
    {

        $data = $this->httpRequest->request->all();

        // get and remove the vpc_TxnResponseCode code from the response fields as we
        // do not want to include this field in the hash calculation
        $vpc_Txn_Secure_Hash = $data['vpc_SecureHash'];
        unset ($data['vpc_SecureHash']);

        // set a flag to indicate if hash has been validated
        $hashValidated = false;
        $SECURE_SECRET = $this->getSecureHash();

        if (strlen($SECURE_SECRET) > 0 && $data['vpc_TxnResponseCode'] != "7" && $data['vpc_TxnResponseCode'] != "No Value Returned") {

            //$stringHashData = $SECURE_SECRET;
            //*****************************khởi tạo chuỗi mã hóa rỗng*****************************
            $stringHashData = "";

            // sort all the incoming vpc response fields and leave out any with no value
            foreach ($data as $key => $value) {
                //        if ($key != "vpc_SecureHash" or strlen($value) > 0) {
                //            $stringHashData .= $value;
                //        }
                //      *****************************chỉ lấy các tham số bắt đầu bằng "vpc_" hoặc "user_" và khác trống và không phải chuỗi hash code trả về*****************************
                if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0,
                                4) == "vpc_") || (substr($key, 0, 5) == "user_"))
                ) {
                    $stringHashData .= $key . "=" . $value . "&";
                }
            }
            //  *****************************Xóa dấu & thừa cuối chuỗi dữ liệu*****************************
            $stringHashData = rtrim($stringHashData, "&");

            //    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md5 ( $stringHashData ) )) {
            //    *****************************Thay hàm tạo chuỗi mã hóa*****************************
            if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $stringHashData,
                    pack('H*', $SECURE_SECRET)))
            ) {
                // Secure Hash validation succeeded, add a data field to be displayed
                // later.
                $hashValidated = true;
            } else {
                // Secure Hash validation failed, add a data field to be displayed later.
            }
        } else {
            // Secure Hash was not validated, add a data field to be displayed later.
        }

        return $hashValidated;
    }

}
