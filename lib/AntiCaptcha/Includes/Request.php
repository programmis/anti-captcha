<?php
/**
 * Created by PhpStorm.
 * User: daniil
 * Date: 16.11.16
 * Time: 15:08
 */

namespace AntiCaptcha\Includes;

use logger\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class Request
 *
 * @package AntiCaptcha\Includes
 */
abstract class Request extends \ApiRator\Includes\Request implements AntiCaptchaInterface
{
    /** @var string $access_token */
    private static $access_token;
    private $json_response;
    /** @var integer $timeout */
    private $timeout = 30;

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Request constructor.
     *
     * @param string          $access_token
     * @param LoggerInterface $logger
     */
    public function __construct($access_token = null, $logger = null)
    {
        if (!$logger && !self::$logger) {
            $logger = new Logger();
        }
        if ($access_token) {
            $this->setAccessToken($access_token);
        }
        parent::__construct(self::MAGIC_PREFIX, $logger);
    }

    /** @inheritdoc */
    public function answerProcessing($content)
    {
        $json = json_decode($content);

        if (!$json || !is_object($json)) {
            return false;
        }
        $this->json_response = $json;

        return true;
    }

    /** @inheritdoc */
    public function handleParameters($parameters)
    {
        $r_params = [];
        foreach ($parameters as $key => $parameter) {
            if (is_array($parameter)) {
                $r_params[$key] = implode(',', $parameter);
            } else {
                $r_params[$key] = $parameter;
            }
        }

        return $r_params;
    }

    /**
     * @param string $access_token
     *
     * @return Request
     */
    public function setAccessToken($access_token)
    {
        self::$access_token = $access_token;
        $this->setParameter("key", $this->getAccessToken());

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJsonResponse()
    {
        return $this->json_response;
    }

    /** @inheritdoc */
    public function getAccessToken()
    {
        return self::$access_token;
    }
}
