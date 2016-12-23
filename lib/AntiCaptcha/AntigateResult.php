<?php
/**
 * Created by PhpStorm.
 * User: daniil
 * Date: 23.12.16
 * Time: 12:18
 */

namespace AntiCaptcha;

/**
 * Class AntigateResult
 *
 * @package AntiCaptcha
 */
class AntigateResult extends RuCaptchaResult
{
    /** @inheritdoc */
    public function getResultApiUrl()
    {
        return 'http://antigate.com/res.php?action=get&id=' . $this->getId() . '&key=' . $this->getAccessToken();
    }
}
