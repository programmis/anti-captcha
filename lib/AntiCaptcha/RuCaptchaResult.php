<?php
/**
 * Created by PhpStorm.
 * User: daniil
 * Date: 16.11.16
 * Time: 16:18
 */

namespace AntiCaptcha;

use AntiCaptcha\Includes\Request;

/**
 * Class RuCaptchaResult
 *
 * @package AntiCaptcha
 */
class RuCaptchaResult extends Request
{
    /** @var string $error_msg */
    private $error_msg;
    /** @var string $text */
    private $text;
    /** @var integer $id */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /** @inheritdoc */
    public function recognize($image_path = '')
    {
        $this->setParameter('json', 'true');
        $result = $this->execApi();
        if ($result && ($json = $this->getJsonResponse())) {
            if (!$json->status) {
                $this->error_msg = $json->request;
                if (self::$logger) {
                    self::$logger->error($this->error_msg);
                }

                return false;
            }
            $this->text = $json->request;

            return true;
        }

        return false;
    }

    /** @inheritdoc */
    public function getText()
    {
        return $this->text;
    }

    /** @inheritdoc */
    public function getErrorMsg()
    {
        return $this->error_msg;
    }

    /** @inheritdoc */
    public function getResultApiUrl()
    {
        return 'http://rucaptcha.com/res.php?action=get&id=' . $this->getId() . '&key=' . $this->getAccessToken();
    }

    /** @inheritdoc */
    public function getMethod()
    {
        return '';
    }

    /** @inheritdoc */
    public function getApiVersion()
    {
        return '';
    }
}