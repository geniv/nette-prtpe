<?php

namespace Prtpe;

use Curl\Curl;
use DateTimeZone;
use Dibi\DateTime;
use Exception;


/**
 * Class Response
 *
 * @author  geniv
 * @package Prtpe
 */
class Response
{
    private $response;
    private $resultCore;


    /**
     * Response constructor.
     *
     * @param Curl $curl
     * @throws Exception
     */
    public function __construct(Curl $curl)
    {
        $this->response = json_decode($curl->response, true);
        $this->resultCore = $this->response['result']['code'];

        if ($curl->isError()) {
            throw new Exception($this->response['result']['description'], null, new Exception($curl->error_message, $curl->error_code));
        }
        $curl->close();
    }


    /**
     * Is success payment.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $this->resultCore);
    }


    /**
     * Get response id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->response['id'];
    }


    /**
     * Get response result.
     *
     * @param null $index
     * @return mixed
     */
    public function getResult($index = null)
    {
        $result = $this->response;
        // convert to system timezone
        $result['timestamp'] = (new DateTime($result['timestamp']))->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if ($index) {
            if (isset($result[$index])) {
                return $result[$index];
            }
        }
        return $result;
    }
}
