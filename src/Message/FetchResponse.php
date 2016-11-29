<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * FetchResponse
 */
class FetchResponse extends AbstractResponse
{

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }


    public function isSuccessful()
    {
        if (isset( $this->data['vpc_DRExists'] ) && $this->data['vpc_DRExists'] == 'Y' && isset( $this->data['vpc_TxnResponseCode'] ) && $this->data['vpc_TxnResponseCode'] == '0') {
            return true;
        } elseif ( ! isset( $this->data['vpc_DRExists'] ) && isset( $this->data['vpc_TxnResponseCode'] ) && $this->data['vpc_TxnResponseCode'] == '0') {
            return true;
        } elseif (isset( $this->data['vpc_ResponseCode'] ) && $this->data['vpc_ResponseCode'] == '0') {
            return true;
        }

        return false;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        if (isset( $this->data['vpc_DRExists'] ) && $this->data['vpc_DRExists'] == 'N') {
            return "Không tồn tại giao dịch";
        } else {
            if (isset( $this->data['vpc_TxnResponseCode'] )) {
                return $this->getResponseDescription($this->data['vpc_TxnResponseCode']);
            }

            return isset( $this->data['vpc_Message'] ) ? $this->data['vpc_Message'] : '';
        }
    }


    /**
     * @return string
     */
    protected function getResponseDescription($responseCode)
    {
        switch ($responseCode) {
            case "0" :
                $result = "Giao dịch thành công - Approved";
                break;
            case "300" :
                $result = "Giao dịch đang chờ - Pending";
                break;
            default :
                $result = "Giao dịch không thanh toán thành công - Failured";
        }

        return $result;
    }

}
