<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Response
 */
class Response extends AbstractResponse
{

    protected $transactionStatus = [
        '0' => 'Approved',
        '1' => 'Bank Declined',
        '3' => 'Merchant Not Exist',
        '4' => 'Invalid Access Code',
        '5' => 'Invalid Amount',
        '6' => 'Invalid Currency Code',
        '7' => 'Unspecified Failure',
        '8' => 'Invalid Card Number',
        '9' => 'Invalid Card Name',
        '10' => 'Expired Card',
        '11' => 'Card Not Registered Service(Internet Banking)',
        '12' => 'Invalid Card Date',
        '13' => 'Exist Amount',
        '21' => 'Insufficient Fund',
        '99' => 'User Cancel',
        'X' => 'Failed'
    ];


    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        if (!is_array($data)) {
            parse_str($data, $this->data);
        } else {
            $this->data = $data;
        }
    }


    public function isSuccessful()
    {
        if (isset($this->data['vpc_TxnResponseCode']) && $this->data['vpc_TxnResponseCode'] == '0') {
            $result = true;
        } elseif (isset($this->data['vpc_ResponseCode']) && $this->data['vpc_ResponseCode'] == '0') {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }


    /**
     * To capture , refund , ...
     *
     * @return mixed
     */
    public function getTransactionReference()
    {
        if (isset($this->data['vpc_TransactionNo'])) {
            return $this->data['vpc_TransactionNo'];
        }

        return null;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        if (isset($this->data['vpc_TxnResponseCode'])) {
            return $this->getResponseDescription($this->data['vpc_TxnResponseCode']);
        } elseif (isset($this->data['vpc_ResponseCode'])) {
            return $this->getResponseDescription($this->data['vpc_ResponseCode']);
        } else {
            return $this->data['vpc_Message'];
        }
    }


    protected function getResponseDescription($responseCode)
    {
        if (array_key_exists($responseCode, $this->transactionStatus)) {
            return $this->transactionStatus[$responseCode];
        }

        return $this->transactionStatus['X'];
    }
}
