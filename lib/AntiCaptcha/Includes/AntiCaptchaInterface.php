<?php
/**
 * Created by PhpStorm.
 * User: daniil
 * Date: 16.11.16
 * Time: 14:44
 */

namespace AntiCaptcha\Includes;

/**
 * Interface AntiCaptchaInterface
 *
 * @package AntiCaptcha\Includes
 */
interface AntiCaptchaInterface
{
    const MAGIC_PREFIX = 'antic';

    /**
     * @param string $image_path
     *
     * @return boolean
     */
    public function recognize($image_path = '');

    /**
     * @return string
     */
    public function getText();

    /**
     * @return string
     */
    public function getErrorMsg();
}
