<?php

namespace Prtpe;

use Curl\Curl;
use Exception;


/**
 * Class Prtpe
 *
 * @author  geniv
 * @package Prtpe
 */
class Prtpe
{
    const TEST = 'https://test.prtpe.com/';
    const LIVE = 'https://prtpe.com/';

    private $parameters = [];
    private $testMode = false;
    private $descriptor = null;


    /**
     * Prtpe constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }


    /**
     * Set test mode.
     *
     * TRUE = devel, FALSE = production
     *
     * @param bool $mode
     */
    public function setTest($mode = true)
    {
        $this->testMode = $mode;
    }


    /**
     * Get test mode.
     *
     * @return bool
     */
    public function isTestMode()
    {
        return $this->testMode;
    }


    /**
     * Internal init curl.
     *
     * @return Curl
     */
    private function initCurl()
    {
        $curl = new Curl;
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, !$this->testMode);
        return $curl;
    }


    /**
     * Get internal authentication.
     *
     * @return array
     */
    private function getAuthentication()
    {
        return [
            'authentication.userId'   => $this->parameters['userId'],
            'authentication.password' => $this->parameters['password'],
            'authentication.entityId' => $this->parameters['entityId'],
        ];
    }


    /**
     * Payment descriptor.
     *
     * @param $text
     * @return $this
     */
    public function setDescriptor($text)
    {
        if (strlen($text) <= 127) {
            $this->descriptor = $text;
        }
        return $this;
    }


    /**
     * Get internal payment descriptor.
     *
     * @return array
     */
    private function getDescriptor()
    {
        return ($this->descriptor ? ['descriptor' => $this->descriptor] : []);
    }


    /**
     * Get payments status.
     *
     * @param $checkoutId
     * @return Response
     */
    public function getStatus($checkoutId)
    {
        $curl = $this->initCurl();
        $url = ($this->testMode ? self::TEST : self::LIVE) . 'v1/payments/' . $checkoutId;
        $curl->get($url, $this->getAuthentication());

        return new Response($curl);
    }


    /**
     * Payment.
     *
     * @param Card   $card
     * @param        $amount
     * @param string $paymentBrand
     * @param string $currency
     * @param string $paymentType
     * @return Response
     */
    public function payment(Card $card, $amount, $paymentBrand = 'VISA', $currency = 'EUR', $paymentType = 'DB')
    {
        $curl = $this->initCurl();
        $url = ($this->testMode ? self::TEST : self::LIVE) . 'v1/payments';
        $curl->post($url, [
                'amount'       => $amount,
                'currency'     => $currency,
                'paymentBrand' => $paymentBrand,
                'paymentType'  => $paymentType,
            ] + $card->toArray() + $this->getDescriptor() + $this->getAuthentication());

        return new Response($curl);
    }


    /**
     * Recurring payment.
     */


    /**
     * Send recurring payment.
     *
     * @param        $registrationId
     * @param        $amount
     * @param string $paymentBrand
     * @param string $currency
     * @param string $paymentType
     * @return Response
     */
    public function sendRepeatedPayment($registrationId, $amount, $paymentBrand = 'VISA', $currency = 'EUR', $paymentType = 'PA')
    {
        $curl = $this->initCurl();
        $url = ($this->testMode ? self::TEST : self::LIVE) . 'v1/registrations/' . $registrationId . '/payments';
        $curl->post($url, [
                'amount'        => $amount,
                'currency'      => $currency,
                'paymentBrand'  => $paymentBrand,
                'paymentType'   => $paymentType,
                'recurringType' => 'REPEATED',
            ] + $this->getDescriptor() + $this->getAuthentication());

        return new Response($curl);
    }


    /**
     * Store data for recurring payment.
     *
     * @param Card   $card
     * @param string $paymentBrand
     * @return Response
     */
    public function storePaymentData(Card $card, $paymentBrand = 'VISA')
    {
        $curl = $this->initCurl();
        $url = ($this->testMode ? self::TEST : self::LIVE) . 'v1/registrations';
        $curl->post($url, [
                'paymentBrand' => $paymentBrand,
            ] + $card->toArray() + $this->getAuthentication());

        return new Response($curl);
    }


    /**
     * Delete recurring payment.
     *
     * @param $registrationId
     * @return Response
     */
    public function deleteStorePaymentData($registrationId)
    {
        $curl = $this->initCurl();
        $url = ($this->testMode ? self::TEST : self::LIVE) . 'v1/registrations/' . $registrationId;
        $curl->delete($url, $this->getAuthentication());

        return new Response($curl);
    }
}
