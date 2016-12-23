<?php
/**
 * Created by PhpStorm.
 * User: daniil
 * Date: 23.12.16
 * Time: 12:01
 */

namespace AntiCaptcha;

/**
 * Class Antigate
 *
 * @package AntiCaptcha
 */
class Antigate extends RuCaptcha
{
    /** @inheritdoc */
    public function getResultApiUrl()
    {
        return 'http://antigate.com/in.php';
    }

    /** @inheritdoc */
    public function getResultClass()
    {
        return new AntigateResult();
    }
}
