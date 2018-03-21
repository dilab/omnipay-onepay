<?php

namespace Omnipay\OnePay;

use Omnipay\Common\AbstractGateway;

/**
 * OnePay Noi Dia Class
 *
 * @link https://mtf.onepay.vn/developer/resource/documents/docx/quy_trinh_tich_hop-noidia.pdf
 */
class NoiDiaGateway extends AbstractGateway
{

    public function getName()
    {
        return 'OnePay Noi Dia';
    }


    public function getDefaultParameters()
    {
        return [
            'vpcAccessCode' => '',
            'vpcMerchant' => '',
            'secureHash' => '',
            'vpcUser' => '',
            'vpcPassword' => '',
            'testMode' => false,
        ];
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


    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }


    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }


    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\NoiDiaPurchaseRequest', $parameters);
    }


    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\NoiDiaCompletePurchaseRequest', $parameters);
    }


    public function fetchCheckout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\NoiDiaFetchRequest', $parameters);
    }


    /**
     * TODO should move to AbstractGateway
     *
     * Create a response object using existing parameters from return url , redirect url
     *
     * @param string $class The response class name, ex: \Omnipay\Payflow\Message\Response
     * @param array $parameters , ex: ["action" => "return", "vpc_TxnResponseCode" => 5, "vpc_Message" => "Amount is
     *                           invalid"]
     *
     * @return object, ex: \Omnipay\Common\Message\Response
     */
    public function createResponse($class, array $parameters, $type)
    {
        return new $class(call_user_func_array([$this, $type], [$parameters]), $parameters);
    }


    public function getResponse(array $parameters = [], $type = 'purchase')
    {
        return $this->createResponse('\Omnipay\OnePay\Message\Response', $parameters, $type);
    }
}
