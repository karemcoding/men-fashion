<?php

namespace common\util;

use common\models\settings\General;
use common\models\settings\Setting;
use ReflectionException;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\queue\db\Queue;

/**
 * Class AppHelper
 * @package common\src
 */
class AppHelper extends BaseObject
{

    /**
     * @return Setting
     * @throws InvalidConfigException
     */
    public static function setting(): Setting
    {
        return Yii::$app->get('setting')->get();
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function mailerQueue(): Queue
    {
        return Yii::$app->get('mailerQueue');
    }

    /**
     * @return string
     */
    public static function webHostRoot()
    {
        $baseUrl = Yii::$app->request->hostInfo;
        $root = basename(Yii::getAlias('@root'));
        return "$baseUrl/$root";
    }

    /**
     * @return string|null
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public static function logo()
    {
        /** @var General $general */
        $general = AppHelper::setting()->model(General::class);
        return $general->previewLogo();
    }
}
