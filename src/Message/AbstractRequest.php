<?php
/**
 * OnePay Abstract Request
 */

namespace Omnipay\OnePay\Message;

use Omnipay\Common\CurrencyTest;
use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{

    const API_VERSION = '2';

    protected $liveEndpoint = 'https://onepay.vn/onecomm-pay/vpc.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/onecomm-pay/vpc.op';

    public function getAmountInteger()
    {
        $result = parent::getAmountInteger();

        if ('VND' == strtoupper($this->getCurrency())) {
            return $result * 100;
        }

        return $result;
    }

    public function getVpcAccessCode()
    {
        return $this->getParameter('vpcAccessCode');
    }


    public function setVpcAccessCode($vpcAccessCode)
    {
        return $this->setParameter('vpcAccessCode', $vpcAccessCode);
    }


    public function getVpcMerchant()
    {
        return $this->getParameter('vpcMerchant');
    }


    public function setVpcMerchant($vpcMerchant)
    {
        return $this->setParameter('vpcMerchant', $vpcMerchant);
    }


    public function getSecureHash()
    {
        return $this->getParameter('secureHash');
    }


    public function setSecureHash($secureHash)
    {
        return $this->setParameter('secureHash', $secureHash);
    }


    public function getVpcUser()
    {
        return $this->getParameter('vpcUser');
    }


    public function setVpcUser($vpcUser)
    {
        return $this->setParameter('vpcUser', $vpcUser);
    }


    public function getVpcPassword()
    {
        return $this->getParameter('vpcPassword');
    }


    public function setVpcPassword($vpcPassword)
    {
        return $this->setParameter('vpcPassword', $vpcPassword);
    }


    public function getVpc_MerchTxnRef()
    {
        return $this->getTransactionId();
    }


    public function setVpc_MerchTxnRef($value)
    {
        return $this->setParameter('vpc_MerchTxnRef', $value);
    }


    protected function getBaseData()
    {
        return [
            'vpc_Merchant' => $this->getVpcMerchant(),
            'vpc_AccessCode' => $this->getVpcAccessCode(),
        ];
    }


    public function sendData($data)
    {
        $url = $this->getEndpoint() . '?' . http_build_query($data, '', '&');
        $httpResponse = $this->httpClient->get($url)->send();

        return $this->createResponse($httpResponse->getBody());
    }


    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }


    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }


    public function generateDataWithChecksum($data)
    {
        // sắp xếp dữ liệu theo thứ tự a-z trước khi nối lại
        // arrange array data a-z before make a hash
        ksort($data);
        // Remove the Virtual Payment Client URL from the parameter hash as we
        // do not want to send these fields to the Virtual Payment Client.
        // bÃ¡Â»Â giÃƒÂ¡ trÃ¡Â»â€¹ url vÃƒÂ  nÃƒÂºt submit ra khÃ¡Â»Âi mÃ¡ÂºÂ£ng dÃ¡Â»Â¯ liÃ¡Â»â€¡u
        unset($data["virtualPaymentClientURL"]);
        unset($data["SubButL"]);
        unset($data["vpc_order_id"]);

        //$stringHashData = $SECURE_SECRET; *****************************Khởi tạo chuỗi dữ liệu mã hóa trống*****************************
        $stringHashData = "";

        foreach ($data as $key => $value) {
            // create the md5 input and URL leaving out any fields that have no value
            // tạo chuỗi đầu dữ liệu những tham số có dữ liệu
            if (strlen($value) > 0) {
                //$stringHashData .= $value; *****************************sử dụng cả tên và giá trị tham số để mã hóa*****************************
                if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0,
                                5) == "user_"))
                ) {
                    $stringHashData .= $key . "=" . $value . "&";
                }
            }
        }
        //*****************************xóa ký tự & ở thừa ở cuối chuỗi dữ liệu mã hóa*****************************
        $stringHashData = rtrim($stringHashData, "&");
        // Create the secure hash and append it to the Virtual Payment Client Data if
        // the merchant secret has been provided.

        // thêm giá trị chuỗi mã hóa dữ liệu được tạo ra ở trên vào cuối url
        //$vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($stringHashData));
        // *****************************Thay hàm mã hóa dữ liệu*****************************
        $data['vpc_SecureHash'] = strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $this->getSecureHash())));

        return $data;
    }

}
