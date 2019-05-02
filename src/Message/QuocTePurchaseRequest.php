<?php

namespace Omnipay\OnePay\Message;

/**
 * QuocTe Purchase Request
 */
class QuocTePurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://onepay.vn/vpcpay/vpcpay.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/vpcpay/vpcpay.op';

    protected $liveEndpointWithPromotion = 'https://onepay.vn/promotion/vpcpr.op';

    protected $testEndpointWithPromotion = 'https://mtf.onepay.vn/promotion/vpcpr.op';

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
            'AgainLink' => urlencode($this->httpRequest->server->get('HTTP_REFERER')),
            'vpc_TicketNo' => $this->httpRequest->getClientIp(),
        ];

        if (!empty($this->getVpcPromotionList())) {
            $data['vpc_PromotionList'] = $this->getVpcPromotionList();
        }

        if (!empty($this->getVpcPromotionAmountList())) {
            $data['vpc_Promotion_Amount_List'] = $this->getVpcPromotionAmountList();
        }

        return array_merge($data, $this->getBaseData());
    }

    public function sendData($data)
    {
        $data = http_build_query($this->generateDataWithChecksum($data), '', '&');

        return $this->response = new QuocTePurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        if (!empty($this->getVpcPromotionList())) {
            return $this->getTestMode() ? $this->testEndpointWithPromotion : $this->liveEndpointWithPromotion;
        }

        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
