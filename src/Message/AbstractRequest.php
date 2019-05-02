<?php
/**
 * OnePay Abstract Request
 */

namespace Omnipay\OnePay\Message;

use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{

    const API_VERSION = '2';

    protected $liveEndpoint = 'https://onepay.vn/onecomm-pay/vpc.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/onecomm-pay/vpc.op';

    public function setVpcPromotionList($vpcPromotionList)
    {
        return $this->setParameter('vpcPromotionList', $vpcPromotionList);
    }

    public function getVpcPromotionList()
    {
        return $this->getParameter('vpcPromotionList');
    }

    public function setVpcPromotionAmountList($vpcPromotionAmountList)
    {
        return $this->setParameter('vpcPromotionAmountList', $vpcPromotionAmountList);
    }

    public function getVpcPromotionAmountList()
    {
        return $this->getParameter('vpcPromotionAmountList');
    }

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
        ksort($data);

        unset($data["virtualPaymentClientURL"]);
        unset($data["SubButL"]);
        unset($data["vpc_order_id"]);

        $stringHashData = "";

        foreach ($data as $key => $value) {
            if (strlen($value) > 0) {
                if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0,
                                5) == "user_"))
                ) {
                    $stringHashData .= $key . "=" . $value . "&";
                }
            }
        }

        $stringHashData = rtrim($stringHashData, "&");
        $data['vpc_SecureHash'] = strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $this->getSecureHash())));

        return $data;
    }

}
