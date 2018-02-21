<?php

namespace Prtpe;


/**
 * Class Billing
 *
 * @author  geniv
 * @package Prtpe
 */
class Billing
{
    private $city;
    private $country;


    /**
     * Billing constructor.
     *
     * @param $city
     * @param $country
     */
    public function __construct($city, $country)
    {
        $this->city = $city;
        $this->country = $country;
    }


    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'billing.city'    => $this->city,
            'billing.country' => $this->country,
        ];
    }
}
