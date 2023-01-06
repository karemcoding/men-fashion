<?php

namespace backend\widgets\chart;

use yii\web\AssetBundle;

/**
 * Class ChartAsset
 *
 * @package backend\widgets\chart
 */
class ChartAsset extends AssetBundle
{

    public $sourcePath = '@backend/widgets/chart/assets';

    public $js = [
        'dist/Chart.min.js',
        'Chart.extension.min.js',
    ];
}
