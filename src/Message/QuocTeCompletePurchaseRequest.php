<?php

namespace Omnipay\OnePay\Message;

/**
 * Quoc Te Complete Purchase Request
 */
class QuocTeCompletePurchaseRequest extends NoiDiaCompletePurchaseRequest
{

    public function getEndpoint()
    {
        if (!empty($this->getVpcPromotionList())) {
            return $this->getTestMode() ? $this->testEndpointInternationalWithPromotion: $this->liveEndpointInternationalWithPromotion;
        }

        return $this->getTestMode() ? $this->testEndpointInternational : $this->liveEndpointInternational;
    }
}
