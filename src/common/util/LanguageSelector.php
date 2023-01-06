<?php

namespace common\util;

use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\web\Application;

/**
 * Class LanguageSelector
 * @package common\util
 *
 * @author Andy Le <ltanh1194@gmail.com>
 *
 * **common/config/main.php
 * 'bootstrap' => ['languageSelector']
 * 'components' => [
 *      'languageSelector' => LanguageSelector::class
 * ]
 * **Logic choose locale:
 * $languageSelector = Yii::$app->languageSelector;
 * Yii::$app->session->set($languageSelector->sessionName,'vi-VN');
 */
class LanguageSelector extends Component implements BootstrapInterface
{
    public $sessionName = 'LANGUAGE_SELECTOR_DEFAULT_LOCALE';

    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $locale = $app->session->get($this->sessionName);
            if ($locale) {
                $app->language = $locale;
            }
        }
    }
}