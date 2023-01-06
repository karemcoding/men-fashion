<?php

namespace common\util;

use Yii;
use yii\base\BaseObject;

/**
 * Class SocialMediaTags
 *
 * @package common\util
 */
class SocialMediaTags extends BaseObject
{

    /**
     * @param $properties
     */
    public static function generate($properties)
    {
        foreach ($properties as $item => $value) {
            Yii::$app->view->registerMetaTag([
                'property' => $item,
                'content' => $value,
            ]);
        }
    }
}
