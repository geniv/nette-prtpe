<?php

namespace Prtpe;


/**
 * Class Card
 *
 * @author  geniv
 * @package Prtpe
 */
class Card
{
    private $number;
    private $holder;
    private $expiryMonth;
    private $expiryYear;
    private $cvv;


    /**
     * Card constructor.
     *
     * @param $number
     * @param $holder
     * @param $expiryMonth
     * @param $expiryYear
     * @param $cvv
     */
    public function __construct($number, $holder, $expiryMonth, $expiryYear, $cvv)
    {
        $this->number = $number;
        $this->holder = $holder;
        $this->expiryMonth = $expiryMonth;
        $this->expiryYear = $expiryYear;
        $this->cvv = $cvv;
    }


    /**
     * Convert to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'card.number'      => $this->number,
            'card.holder'      => $this->holder,
            'card.expiryMonth' => $this->expiryMonth,
            'card.expiryYear'  => $this->expiryYear,
            'card.cvv'         => $this->cvv,
        ];
    }
}
