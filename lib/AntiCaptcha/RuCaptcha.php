<?php
/**
 * Created by PhpStorm.
 * User: daniil
 * Date: 16.11.16
 * Time: 14:44
 */

namespace AntiCaptcha;

use AntiCaptcha\Includes\Request;

/**
 * Class RuCaptcha
 *
 * @package AntiCaptcha
 */
class RuCaptcha extends Request
{
    /** @var string $error_msg */
    private $error_msg;
    /** @var string $text */
    private $text;

    /**
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->error_msg;
    }

    /** @inheritdoc */
    public function getText()
    {
        return $this->text;
    }

    /**
     * false = одно слово (значение по умлочанию)
     * true = капча имеет два слова
     *
     * @param boolean $phrase
     */
    public function setPhrase($phrase)
    {
        $this->antic_phrase = $phrase ? '1' : '0';

        return $this;
    }

    /**
     * false = регистр ответа не имеет значения (значение по умолчанию )
     * true = регистр ответа имеет значение
     *
     * @param boolean $regsense
     */
    public function setRegsense($regsense)
    {
        $this->antic_regsense = $regsense ? '1' : '0';

        return $this;
    }

    /**
     * false = параметр не задействован (значение по умолчанию )
     * true = на изображении задан вопрос, работник должен написать ответ
     *
     * @param boolean $question
     */
    public function setQuestion($question)
    {
        $this->antic_question = $question ? '1' : '0';

        return $this;
    }

    /**
     * 0 = параметр не задействован (значение по умолчанию)
     * 1 = капча состоит только из цифр
     * 2 = Капча состоит только из букв
     * 3 = Капча состоит либо только из цифр, либо только из букв.
     *
     * @param int $numeric
     */
    public function setNumeric($numeric)
    {
        $this->antic_numeric = $numeric;

        return $this;
    }

    /**
     * false = параметр не задействован (значение по умолчанию)
     * true = работнику нужно совершить математическое действие с капчи
     *
     * @param boolean $calc
     */
    public function setCalc($calc)
    {
        $this->antic_calc = $calc ? '1' : '0';

        return $this;
    }

    /**
     * 0 = параметр не задействован (значение по умолчанию)
     * 1..20 = минимальное количество знаков в ответе
     *
     * @param int $min_len
     */
    public function setMinLen($min_len)
    {
        $this->antic_min_len = $min_len;

        return $this;
    }

    /**
     * 0 = параметр не задействован (значение по умолчанию)
     * 1..20 = максимальное количество знаков в ответе
     *
     * @param int $max_len
     */
    public function setMaxLen($max_len)
    {
        $this->antic_max_len = $max_len;

        return $this;
    }

    /**
     * параметр больше не используется, т.к. он означал "слать данную капчу русским исполнителям",
     * а в системе находятся только русскоязычные исполнители. Смотрите новый параметр language, однозначно обозначающий язык капчи
     *
     * @param boolean $is_russian
     */
    public function setIsRussian($is_russian)
    {
        $this->antic_is_russian = $is_russian ? '1' : '0';

        return $this;
    }

    /**
     * ID разработчика приложения. Разработчику приложения отчисляется 10% от всех капч, пришедших из его приложения.
     *
     * @param int $soft_id
     */
    public function setSoftId($soft_id)
    {
        $this->antic_soft_id = $soft_id;

        return $this;
    }

    /**
     * 0 = параметр не задействован (значение по умолчанию)
     * 1 = на капче только кириллические буквы
     * 2 = на капче только латинские буквы
     *
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->antic_language = $language;

        return $this;
    }

    /**
     * false = значение по умолчанию
     * true = in.php передаст Access-Control-Allow-Origin: * параметр в заголовке ответа.
     * (Необходимо для кросс-доменных AJAX запросов в браузерных приложениях. Работает также для res.php.)
     *
     * @param boolean $header_acao
     */
    public function setHeaderAcao($header_acao)
    {
        $this->antic_header_acao = $header_acao ? '1' : '0';

        return $this;
    }

    /**
     * Текст, который будет показан работнику. Может содержать в себе инструкции по разгадке капчи. Ограничение - 140 символов.
     * Текст необходимо слать в кодировке UTF-8.
     *
     * @param string $textinstructions
     */
    public function setTextinstructions($textinstructions)
    {
        $this->antic_textinstructions = $textinstructions;

        return $this;
    }

    /**
     * Текстовая капча. Картинка при этом не загружается, работник получает только текст и вводит ответ на этот текст.
     * Ограничение - 140 символов. Текст необходимо слать в кодировке UTF-8.
     *
     * @param string $textcaptcha
     */
    public function setTextcaptcha($textcaptcha)
    {
        $this->antic_textcaptcha = $textcaptcha;

        return $this;
    }

    /**
     * Указание для сервера, что после распознания изображения, нужно отправить ответ на указанный адрес
     *
     * @param string $pingback
     */
    public function setPingback($pingback)
    {
        $this->antic_pingback = $pingback;

        return $this;
    }

    /** @inheritdoc */
    public function recognize($image_path = '')
    {
        $start_time = time();

        if ($image_path) {
            $body = "";
            if (file_exists($image_path)) {
                $fp = fopen($image_path, "r");
                if ($fp != false) {
                    while (!feof($fp)) {
                        $body .= fgets($fp, 1024);
                    }
                    fclose($fp);
                } else {
                    if (self::$logger) {
                        self::$logger->critical("could not read file $image_path");
                    }

                    return false;
                }
            } else {
                $body = file_get_contents($image_path, FILE_BINARY);
            }
            if (!$body) {
                if (self::$logger) {
                    self::$logger->critical("file $image_path couldn't by empty");
                }

                return false;
            }
            $this->setParameter('method', 'base64');
            $this->setParameter('body', base64_encode($body));
        }
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
            $captcha_id = $json->request;
            while (true) {
                if (time() - $start_time > $this->getTimeout()) {
                    if (self::$logger) {
                        self::$logger->error('exit by timeout: ' . $this->getTimeout() . ' seconds');
                    }

                    break;
                }
                $result = new RuCaptchaResult();
                $result->setAccessToken($this->getAccessToken());
                $result->setId($captcha_id);
                if ($result->recognize()) {
                    $this->text = $result->getText();

                    return true;
                } else {
                    if ($result->getErrorMsg() != "CAPCHA_NOT_READY") {
                        return false;
                    }
                    sleep(1);
                }
            }
        }

        return false;
    }

    /** @inheritdoc */
    public function getResultApiUrl()
    {
        return 'http://rucaptcha.com/in.php';
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
