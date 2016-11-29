<?php

namespace Omnipay\OnePay;

/**
 * OnePay Quoc Te Class
 *
 * @link https://mtf.onepay.vn/developer/resource/documents/docx/quy_trinh_tich_hop-quocte.pdf
 */
class QuocTeGateway extends NoiDiaGateway
{

    public function getName()
    {
        return 'OnePay Quoc Te';
    }


    public function purchase(array $parameters = [ ])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\QuocTePurchaseRequest', $parameters);
    }


    public function completePurchase(array $parameters = [ ])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\QuocTeFetchRequest', $parameters);
    }


    public function fetchCheckout(array $parameters = [ ])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\QuocTeFetchRequest', $parameters);
    }


    public function getResponse(array $parameters = [ ], $type = 'purchase')
    {
        return $this->createResponse('\Omnipay\OnePay\Message\QuocTePurchaseResponse', $parameters, $type);
    }

}
