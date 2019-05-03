<?php
namespace Omnipay\OnePay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * NoiDia Purchase Response
 */
class NoiDiaPurchaseResponse extends Response implements RedirectResponseInterface
{

    public function isRedirect()
    {
        return true;
    }


    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint() . '?' . http_build_query($this->data, '', '&');
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectData()
    {
        return null;
    }


    protected function getCheckoutEndpoint()
    {
        return $this->getRequest()->getEndpoint();
    }
}
