<?php

namespace common\widgets\ga;

use yii\base\Widget;

/**
 * Class GoogleAnalyticsWidget
 *
 * @package common\widgets\googleAnalytics
 */
class Ga extends Widget
{

    public $ga_tracking_id = NULL;

    /**
     * @return string|void
     */
    public function run()
    {
        if ($this->ga_tracking_id) {
            return $this->render('ga', ['id' => $this->ga_tracking_id]);
        }
    }
}
