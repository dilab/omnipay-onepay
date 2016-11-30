<?php

namespace Omnipay\OnePay\Message;

/**
 * NoiDia Purchase Request
 */
class NoiDiaPurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('amount');

        $data = [
            'vpc_order_id' => $this->getTransactionId(),
            'Title' => 'VPC 3-Party',
            'virtualPaymentClientURL' => $this->getEndpoint(),
            'vpc_Version' => $this::API_VERSION,
            'vpc_Command' => 'pay',
            'vpc_MerchTxnRef' => $this->getTransactionId(),
            'vpc_OrderInfo' => $this->getTransactionId(),
            'vpc_Amount' => $this->getAmountInteger(),
            'vpc_Locale' => $this->httpRequest->getLocale(),
            'vpc_ReturnURL' => $this->getReturnUrl(),
            'vpc_TicketNo' => $this->httpRequest->getClientIp(),
            'vpc_Currency' => $this->getCurrency()
        ];

        return array_merge($data, $this->getBaseData());
    }


    public function sendData($data)
    {
        $data = http_build_query($this->generateDataWithChecksum($data), '', '&');

        return $this->response = new NoiDiaPurchaseResponse($this, $data);
    }

}
