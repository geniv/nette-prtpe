<?php

namespace Prtpe;

use Curl\Curl;
use DateTime;
use DateTimeZone;
use Exception;


/**
 * Class Response
 *
 * @author  geniv
 * @package Prtpe
 */
class Response
{
    /** @var Curl */
    private $curl;
    private $response;
    private $resultCode;


    /**
     * Response constructor.
     *
     * @param Curl $curl
     * @throws Exception
     */
    public function __construct(Curl $curl)
    {
        $this->curl = $curl;

        $this->response = json_decode($curl->response, true);
        $this->resultCode = $this->response['result']['code'];

        if ($curl->isError()) {
            $ex = new Exception($curl->error_message, $curl->error_code);
            if (isset($this->response['result']['parameterErrors'])) {
                foreach ($this->response['result']['parameterErrors'] as $error) {
                    $ex = new Exception($error['name'] . ' value: "' . $error['value'] . '" ' . $error['message'], null, $ex);
                }
            }
            throw new Exception($this->response['result']['description'], null, $ex);
        }
        $curl->close();
    }


    /**
     * Is success.
     *
     * @return bool
     */
    public function isSuccess()
    {
        if ($this->curl->isSuccess()) {
            return (preg_match('/^(000\.200)/', $this->resultCode) || preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $this->resultCode));
        }
        return false;
    }


    /**
     * Get result code.
     *
     * @return mixed
     */
    public function getResultCode()
    {
        return $this->resultCode;
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
     * Get registration id.
     *
     * @return null
     */
    public function getRegistrationId()
    {
        return (isset($this->response['registrationId']) ? $this->response['registrationId'] : null);
    }


    /**
     * Get response result.
     *
     * @param null $index
     * @param null $timeZone
     * @return mixed
     */
    public function getResult($index = null, $timeZone = null)
    {
        $result = $this->response;
        if (!$timeZone) {
            $timeZone = date_default_timezone_get();
        }

        // convert to system timezone
        $result['timestamp'] = (new DateTime($result['timestamp']))->setTimezone(new DateTimeZone($timeZone));

        if ($index) {
            if (isset($result[$index])) {
                return $result[$index];
            }
        }
        return $result;
    }
}
