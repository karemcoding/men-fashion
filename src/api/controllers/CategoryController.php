<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:29 AM 5/3/2021
 * @projectName baseProject by ANDY
 */

namespace api\controllers;

use api\models\Category;
use yii\db\ActiveRecord;

/**
 * Class CategoryController
 * @package api\controllers
 */
class CategoryController extends ActiveController
{
    /**
     * @return Category[]|array|ActiveRecord[]
     */
    public function actionIndex()
    {
        return Category::getAll();
    }
    public function actionOne($id)
    {
        return Category::getOne($id);
    }
}