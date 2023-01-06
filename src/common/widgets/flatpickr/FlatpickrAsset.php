<?php

namespace common\widgets\flatpickr;

use yii\helpers\ArrayHelper;
use yii\web\AssetBundle;

/**
 * Class FlatpickrAsset
 *
 * @package common\widgets\flatpickr
 */
class FlatpickrAsset extends AssetBundle
{

    public $locale;

    public $theme;

    public $plugins = [];

    public $css = [
        'flatpickr.min.css'
    ];

    public $js = [
        'flatpickr.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
        parent::init();
    }

    /**
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        // check language, file en.js do not exist
        if (!empty($this->locale) && ($this->locale !== 'en')) {
            $this->js[] = 'l10n/' . $this->locale . '.js';
        }

        // check theme
        if (!empty($this->theme)) {
            $this->css[] = 'themes/' . $this->theme . '.css';
        }

        // check plugin
        if (!empty($this->plugins) && is_array($this->plugins)) {
            //rangePlugin with 2 input and handling value field date from
            if (ArrayHelper::keyExists('rangePlugin', $this->plugins)) {
                $this->js[] = 'plugins/rangePlugin.js';
            }
            if (ArrayHelper::keyExists('minMaxTimePlugin', $this->plugins)) {
                $this->js[] = 'plugins/minMaxTimePlugin.js';
            }
            if (ArrayHelper::keyExists('confirmDatePlugin', $this->plugins)) {
                $this->js[] = 'plugins/confirmDate/confirmDate.js';
                $this->css[] = 'plugins/confirmDate/confirmDate.css';
            }
            if (ArrayHelper::keyExists('labelPlugin', $this->plugins)) {
                $this->js[] = 'plugins/labelPlugin/labelPlugin.js';
            }
            if (ArrayHelper::keyExists('scrollPlugin', $this->plugins)) {
                $this->js[] = 'plugins/scrollPlugin.js';
            }
            if (ArrayHelper::keyExists('weekSelect', $this->plugins)) {
                $this->js[] = 'plugins/weekSelect/weekSelect.js';
            }
            if (ArrayHelper::keyExists('monthSelectPlugin', $this->plugins)) {
                $this->js[] = 'plugins/monthSelect/index.js';
                $this->css[] = 'plugins/monthSelect/style.css';
            }
        }

        parent::registerAssetFiles($view);
    }
}
