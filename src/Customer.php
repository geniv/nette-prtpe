<?php

namespace Prtpe;


/**
 * Class Customer
 *
 * @author  geniv
 * @package Prtpe
 */
class Customer
{
    private $givenName;
    private $surname;
    private $email;
    private $ip;


    /**
     * Customer constructor.
     *
     * @param $givenName
     * @param $surname
     * @param $email
     * @param $ip
     */
    public function __construct($givenName, $surname, $email, $ip)
    {
        $this->givenName = $givenName;
        $this->surname = $surname;
        $this->email = $email;
        $this->ip = $ip;
    }


    /**
     * To array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'customer.givenName' => $this->givenName,
            'customer.surname'   => $this->surname,
            'customer.email'     => $this->email,
            'customer.ip'        => $this->ip,
        ];
    }
}
