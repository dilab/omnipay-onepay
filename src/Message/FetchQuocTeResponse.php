<?php

namespace Omnipay\OnePay\Message;

/**
 * FetchQuocTeResponse
 */
class FetchQuocTeResponse extends FetchResponse
{

    /**
     * @return string
     */
    protected function getResponseDescription($responseCode)
    {
        switch ($responseCode) {
            case '0' :
                $result = 'Transaction successful';
                break;
            case '?' :
                $result = 'Transaction status is unknown';
                break;
            case '1' :
                $result = 'Bank system reject';
                break;
            case '2' :
                $result = 'Bank declined transaction';
                break;
            case '3' :
                $result = 'No reply from bank';
                break;
            case '4' :
                $result = 'Expired card';
                break;
            case '5' :
                $result = 'Insufficient funds';
                break;
            case '6' :
                $result = 'Error communicating with bank';
                break;
            case '7' :
                $result = 'Payment server system error';
                break;
            case '8' :
                $result = 'Transaction type not supported';
                break;
            case '9' :
                $result = 'Bank declined transaction (do not contact bank)';
                break;
            case 'A' :
                $result = 'Transaction aborted';
                break;
            case 'C' :
                $result = 'Transaction cancelled';
                break;
            case 'D' :
                $result = 'Deferred transaction has been received and is awaiting processing';
                break;
            case 'F' :
                $result = '3D secure authentication failed';
                break;
            case 'I' :
                $result = 'Card security code verification failed';
                break;
            case 'L' :
                $result = 'Shopping transaction locked (please try the transaction again later)';
                break;
            case 'N' :
                $result = 'Cardholder is not enrolled in authentication scheme';
                break;
            case 'P' :
                $result = 'Transaction has been received by the payment adaptor and is being processed';
                break;
            case 'R' :
                $result = 'Transaction was not processed - reached limit of retry attempts allowed';
                break;
            case 'S' :
                $result = 'Duplicate sessionID (OrderInfo)';
                break;
            case 'T' :
                $result = 'Address verification failed';
                break;
            case 'U' :
                $result = 'Card security code failed';
                break;
            case 'V' :
                $result = 'Address verification and card security code failed';
                break;
            default  :
                $result = 'Unable to be determined';
        }

        return $result;
    }

}
